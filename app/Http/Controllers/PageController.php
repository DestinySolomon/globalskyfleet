<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function services()
    {
        return view('pages.services');
    }

    public function tracking(Request $request)
    {
        $trackingNumber = $request->query('tracking_number');
        
        // For demo purposes, we'll simulate a tracking result
        $trackingResult = null;
        if ($trackingNumber) {
            $trackingResult = [
                'tracking_number' => $trackingNumber,
                'status' => 'in_transit',
                'estimated_delivery' => now()->addDays(3)->format('M d'),
                'weight' => '2.5kg',
                'service' => 'Express',
                'from' => 'Shanghai, China',
                'to' => 'New York, USA'
            ];
        }
        
        return view('pages.tracking', [
            'trackingResult' => $trackingResult,
            'trackingNumber' => $trackingNumber
        ]);
    }

    public function trackingSubmit(Request $request)
    {
        // Validate the tracking number
        $request->validate([
            'tracking_number' => 'required|string|min:10|max:20'
        ]);
        
        // In a real application, you would:
        // 1. Look up the tracking number in your database
        // 2. Fetch tracking information from carrier API if needed
        // 3. Return the tracking result
        
        // For now, redirect back with the tracking number
        return redirect()->route('tracking', [
            'tracking_number' => $request->tracking_number
        ]);
    }

    public function quote()
    {
        return view('pages.quote');
    }

    public function quoteSubmit(Request $request)
    {
        // Validate the form
        $validated = $request->validate([
            'pickup_country' => 'required|string|max:50',
            'delivery_country' => 'required|string|max:50',
            'weight' => 'required|numeric|min:0.1',
            'package_type' => 'required|string|in:Document,Parcel,Pallet,Container',
            'email' => 'required|email',
            'phone' => 'required|string|max:20'
        ]);
        
        // Calculate quote (simplified calculation)
        $price = $this->calculateQuotePrice(
            $validated['pickup_country'],
            $validated['delivery_country'],
            $validated['weight'],
            $validated['package_type']
        );
        
        // Format price
        $formattedPrice = '$' . number_format($price, 2);
        
        // Country mapping for display names
        $countryNames = [
            'USA' => 'United States',
            'UK' => 'United Kingdom',
            'Canada' => 'Canada',
            'Germany' => 'Germany',
            'France' => 'France',
            'China' => 'China',
            'Japan' => 'Japan',
            'Australia' => 'Australia',
            'India' => 'India',
            'Brazil' => 'Brazil'
        ];
        
        // Store quote result in session
        $request->session()->flash('quote_result', [
            'price' => $formattedPrice,
            'pickup' => $countryNames[$validated['pickup_country']] ?? $validated['pickup_country'],
            'delivery' => $countryNames[$validated['delivery_country']] ?? $validated['delivery_country'],
            'weight' => $validated['weight'] . ' kg',
            'type' => $validated['package_type']
        ]);
        
        // In a real application, you would:
        // 1. Save the quote request to the database
        // 2. Send email notifications
        // 3. Maybe integrate with a CRM
        
        return redirect()->route('quote');
    }

    private function calculateQuotePrice($pickup, $delivery, $weight, $type)
    {
        // Base cost by package type
        $baseCost = match($type) {
            'Document' => 50,
            'Parcel' => 100,
            'Pallet' => 300,
            'Container' => 1000,
            default => 100
        };
        
        // Weight multiplier
        $weightMultiplier = max(1, $weight / 10);
        
        // Distance factor (simplified)
        $distanceFactor = $pickup === $delivery ? 1 : 1.5;
        
        // Calculate final price
        $price = $baseCost * $weightMultiplier * $distanceFactor;
        
        // Add random variation for realism
        $price = $price * (0.9 + (rand(0, 20) / 100));
        
        // Round to 2 decimal places
        return round($price, 2);
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactSubmit(Request $request)
    {
        // Enhanced validation rules
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[\p{L}\p{M}\s\'\-.]+$/u', // Allows letters, spaces, apostrophes, hyphens, dots
                function ($attribute, $value, $fail) {
                    // Block common SQL injection patterns
                    $dangerousPatterns = [
                        '/\b(SELECT|INSERT|UPDATE|DELETE|DROP|UNION|CREATE|ALTER|EXEC|DECLARE)\b/i',
                        '/--/',
                        '/;/',
                        '/\/\*/',
                        '/\*\//',
                        '/<script/i',
                        '/<\/script>/i',
                        '/javascript:/i',
                        '/onclick|onload|onerror/i',
                    ];
                    
                    foreach ($dangerousPatterns as $pattern) {
                        if (preg_match($pattern, $value)) {
                            $fail('The ' . $attribute . ' contains invalid characters.');
                        }
                    }
                }
            ],
            'email' => [
                'required',
                'email:rfc,dns', // Strict email validation
                'max:100',
                function ($attribute, $value, $fail) {
                    // Additional email validation
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $fail('The email address is not valid.');
                    }
                    
                    // Check for suspicious patterns
                    if (preg_match('/[\<\>\"\'\(\)\&\;]/', $value)) {
                        $fail('The email address contains invalid characters.');
                    }
                }
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^[\+\d\s\-\(\)\.]+$/', // Only allows +, digits, spaces, -, (, ), .
            ],
            'subject' => [
                'required',
                'string',
                'in:general,quote,tracking,partnership,complaint,other'
            ],
            'message' => [
                'required',
                'string',
                'min:10',
                'max:2000',
                function ($attribute, $value, $fail) {
                    // Basic XSS prevention
                    $dangerousTags = ['<script', '</script>', '<iframe', '</iframe>', '<object', '</object>'];
                    foreach ($dangerousTags as $tag) {
                        if (stripos($value, $tag) !== false) {
                            $fail('The message contains invalid content.');
                        }
                    }
                    
                    // Check for SQL injection patterns
                    $sqlPatterns = ['SELECT ', 'INSERT ', 'UPDATE ', 'DELETE ', 'DROP ', 'UNION ', '--', ';'];
                    foreach ($sqlPatterns as $pattern) {
                        if (stripos($value, $pattern) !== false) {
                            $fail('The message contains invalid content.');
                        }
                    }
                }
            ]
        ]);
        
        // Additional sanitization
        $sanitizedData = [
            'name' => $this->sanitizeInput($validated['name']),
            'email' => filter_var($validated['email'], FILTER_SANITIZE_EMAIL),
            'phone' => $this->sanitizePhone($validated['phone']),
            'subject' => $validated['subject'],
            'message' => $this->sanitizeMessage($validated['message']),
        ];
        
        try {
            // Save to database
            $contactMessage = \App\Models\ContactMessage::create([
                'name' => $sanitizedData['name'],
                'email' => $sanitizedData['email'],
                'phone' => $sanitizedData['phone'],
                'subject' => $sanitizedData['subject'],
                'message' => $sanitizedData['message'],
                'ip_address' => $request->ip(),
                'user_agent' => $this->sanitizeUserAgent($request->userAgent()),
                'status' => 'unread'
            ]);
            
            // Send email notification to admin
            try {
                $adminEmail = config('mail.from.address', 'admin@globalskyfleet.com');
                \Mail::to($adminEmail)->send(new \App\Mail\ContactMessageMail($contactMessage));
                
                // Also send to support email if different
                $supportEmail = 'support@globalskyfleet.com';
                if ($supportEmail && $supportEmail !== $adminEmail) {
                    \Mail::to($supportEmail)->send(new \App\Mail\ContactMessageMail($contactMessage));
                }
                
            } catch (\Exception $e) {
                \Log::error('Failed to send contact email: ' . $e->getMessage());
                // Don't show error to user, just log it
            }
            
            // Create in-app notification for admin users
            $this->createAdminNotification($contactMessage);
            
            // Flash success message
            $request->session()->flash('success', 'Message sent successfully! Our team will get back to you within 24 hours.');
            
        } catch (\Exception $e) {
            \Log::error('Contact form submission error: ' . $e->getMessage());
            
            // Flash error message
            $request->session()->flash('error', 'Failed to send message. Please try again or contact us directly.');
            
            return redirect()->route('contact')
                ->withInput()
                ->withErrors(['general' => 'Failed to send message. Please try again.']);
        }
        
        return redirect()->route('contact');
    }

    // Helper sanitization methods
    private function sanitizeInput($input)
    {
        // Remove HTML tags
        $input = strip_tags($input);
        
        // Convert special characters to HTML entities
        $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Remove extra whitespace
        $input = trim($input);
        
        return $input;
    }

    private function sanitizePhone($phone)
    {
        // Remove all non-numeric characters except +, -, (, ), ., and space
        $phone = preg_replace('/[^\+\d\s\-\(\)\.]/', '', $phone);
        
        return trim($phone);
    }

    private function sanitizeMessage($message)
    {
        // Allow some basic HTML for formatting, but sanitize it
        $allowedTags = '<p><br><b><strong><i><em><ul><ol><li>';
        $message = strip_tags($message, $allowedTags);
        
        // Convert remaining HTML entities
        $message = htmlspecialchars($message, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Limit line breaks to prevent flooding
        $message = preg_replace('/(\r?\n){3,}/', "\n\n", $message);
        
        return trim($message);
    }

    private function sanitizeUserAgent($userAgent)
    {
        if (empty($userAgent)) {
            return 'Unknown';
        }
        
        // Limit length and sanitize
        $userAgent = substr($userAgent, 0, 255);
        $userAgent = htmlspecialchars($userAgent, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        return $userAgent;
    }

  private function createAdminNotification($contactMessage)
{
    try {
        // Get all admin users
        $adminUsers = \App\Models\User::where('role', 'admin', 'super_admin')->get();
        
        foreach ($adminUsers as $admin) {
            $admin->notify(new \App\Notifications\NewContactMessageNotification($contactMessage));
        }
        
        \Log::info('Admin notifications created for contact message #' . $contactMessage->id);
    } catch (\Exception $e) {
        \Log::error('Failed to create admin notification: ' . $e->getMessage());
    }
}

    // REMOVED: login(), loginSubmit(), register(), registerSubmit(), passwordRequest(), socialLogin()
    // These are now handled by Auth controllers

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function newsletterSubscribe(Request $request)
    {
        // Handle newsletter subscription
        $request->validate([
            'email' => 'required|email'
        ]);

        // Here you would typically save to database
        // For now, we'll just redirect back with a success message
        return back()->with('success', 'Thank you for subscribing to our newsletter!');
    }

    // Add this method as referenced in your routes
    public function contactSales()
    {
        return view('pages.contact-sales'); // Create this view if needed
    }

    public function airFreight()
    {
        return view('pages.services.air-freight', [
            'title' => 'Air Freight Services | GlobalSkyFleet',
            'description' => 'Fast and reliable air freight solutions for time-sensitive shipments. International air cargo services with real-time tracking.',
            'keywords' => 'air freight, air cargo, international air shipping, air freight services, express air freight'
        ]);
    }

    public function seaFreight()
    {
        return view('pages.services.sea-freight', [
            'title' => 'Sea Freight & Ocean Shipping | GlobalSkyFleet',
            'description' => 'Cost-effective sea freight solutions for bulk shipments. FCL and LCL container shipping worldwide.',
            'keywords' => 'sea freight, ocean shipping, container shipping, FCL, LCL, maritime logistics'
        ]);
    }

    public function roadCourier()
    {
        return view('pages.services.road-courier', [
            'title' => 'Road Courier & Domestic Shipping | GlobalSkyFleet',
            'description' => 'Reliable road courier services for domestic and cross-border shipments. Fast delivery with comprehensive tracking.',
            'keywords' => 'road courier, domestic shipping, trucking, land transport, cross-border shipping'
        ]);
    }

    public function expressDelivery()
    {
        return view('pages.services.express-delivery', [
            'title' => 'Express Delivery Services | GlobalSkyFleet',
            'description' => 'Priority express delivery for urgent shipments. Same-day and next-day delivery options available.',
            'keywords' => 'express delivery, same-day delivery, next-day delivery, priority shipping, urgent courier'
        ]);
    }

    public function warehousing()
    {
        return view('pages.services.warehousing', [
            'title' => 'Warehousing & Logistics Solutions | GlobalSkyFleet',
            'description' => 'Secure warehousing and inventory management solutions. Storage, fulfillment, and distribution services.',
            'keywords' => 'warehousing, storage solutions, inventory management, fulfillment, logistics, distribution'
        ]);
    }
}