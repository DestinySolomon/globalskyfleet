<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        // If user is already authenticated, check if they should be redirected
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }
        
        return view('pages.login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        // Validate the login request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Regenerate session for security
            $request->session()->regenerate();
            
            // Get the authenticated user
            $user = Auth::user();
            
            // Check for redirect parameter from quote flow
            $redirect = $request->query('redirect');
            if ($redirect) {
                return redirect($redirect)->with('success', 'Welcome back!');
            }
            
            // Redirect based on user role (admin or regular user)
            return $this->redirectBasedOnRole();
        }

        // If authentication fails
        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Redirect user based on their role.
     */
   /**
 * Redirect user based on their role.
 */
private function redirectBasedOnRole()
{
    $user = Auth::user();
    
    // Check if user is admin OR super admin
    if ($user->isAdminOrSuperAdmin()) {
        // For admins/super admins, check if they were trying to access a specific page
        if (Session::has('url.intended')) {
            $intendedUrl = Session::get('url.intended');
            Session::forget('url.intended');
            
            // Don't redirect to admin pages if they were trying to access user pages
            if (!str_contains($intendedUrl, '/admin/')) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Welcome back, Admin!');
            }
            
            return redirect($intendedUrl)->with('success', 'Welcome back!');
        }
        
        // Customize welcome message based on role
        $welcomeMessage = $user->isSuperAdmin() 
            ? 'Welcome back, Super Admin!' 
            : 'Welcome back, Admin!';
            
        return redirect()->route('admin.dashboard')
            ->with('success', $welcomeMessage);
    }
    
    // For regular users, redirect to intended page or dashboard
    return redirect()->intended('dashboard')->with('success', 'Welcome back!');
}

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'You have been logged out successfully.');
    }
}