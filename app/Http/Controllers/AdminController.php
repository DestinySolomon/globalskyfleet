<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shipment;
use App\Models\Document;
use App\Models\Payment;
use App\Models\CryptoPayment;
use App\Models\CryptoAddress;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AdminController extends Controller
{

 public function __construct()
    {
        // Share notification data with all admin views
        View::composer('layouts.admin', function ($view) {
            $unreadNotificationsCount = Auth::user()->unreadNotifications()->count();
            $recentNotifications = Auth::user()->notifications()
                ->latest()
                ->limit(5)
                ->get();
            
            $view->with([
                'unreadNotificationsCount' => $unreadNotificationsCount,
                'recentNotifications' => $recentNotifications
            ]);
        });
    }
    
    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        $stats = $this->getDashboardStats();
        
        // Recent shipments with user and addresses
        $recentShipments = Shipment::with(['user', 'senderAddress', 'recipientAddress'])
            ->latest()
            ->limit(8)
            ->get();
            
        // Recent users (last 5)
        $recentUsers = User::latest()
            ->limit(5)
            ->get();
            
        // Pending crypto payments count
        $pendingCryptoPayments = CryptoPayment::where('status', 'pending')->count();
        
        // Pending documents count
        $pendingDocuments = Document::where('status', 'pending')->count();
        
        // Active wallets count
        $activeWallets = CryptoAddress::where('is_active', true)->count();
        
        // Add additional stats
        $stats['pending_documents'] = $pendingDocuments;
        $stats['active_wallets'] = $activeWallets;
        $stats['crypto_payments_pending'] = $pendingCryptoPayments;
        
        // Calculate total revenue (from completed payments)
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $stats['total_revenue'] = $totalRevenue;
        
        // Get unread notifications count for current admin
        $notificationCount = Auth::user()->unreadNotifications()->count();
        
        return view('admin.dashboard', compact(
            'stats', 
            'recentShipments', 
            'recentUsers',
            'notificationCount'
        ));
    }
    
    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        
        // Total counts
        $totalUsers = User::count();
        $totalShipments = Shipment::count();
        
        // Today's counts
        $newUsersToday = User::whereDate('created_at', $today)->count();
        $shipmentsToday = Shipment::whereDate('created_at', $today)->count();
        
        // Yesterday's counts for comparison
        $newUsersYesterday = User::whereDate('created_at', $yesterday)->count();
        $shipmentsYesterday = Shipment::whereDate('created_at', $yesterday)->count();
        
        // Shipment status counts
        $pendingShipments = Shipment::whereIn('status', ['pending', 'confirmed'])->count();
        $inTransitShipments = Shipment::whereIn('status', ['picked_up', 'in_transit', 'customs_hold', 'out_for_delivery'])->count();
        $deliveredShipments = Shipment::where('status', 'delivered')->count();
        
        // Payment counts
        $totalPayments = Payment::count();
        $pendingPayments = Payment::where('status', 'pending')->count();
        $completedPayments = Payment::where('status', 'completed')->count();
        
        // Document counts
        $totalDocuments = Document::count();
        $documentsToday = Document::whereDate('created_at', $today)->count();
        
        return [
            'total_users' => $totalUsers,
            'new_users_today' => $newUsersToday,
            'new_users_yesterday' => $newUsersYesterday,
            'total_shipments' => $totalShipments,
            'shipments_today' => $shipmentsToday,
            'shipments_yesterday' => $shipmentsYesterday,
            'pending_shipments' => $pendingShipments,
            'in_transit_shipments' => $inTransitShipments,
            'delivered_shipments' => $deliveredShipments,
            'total_payments' => $totalPayments,
            'pending_payments' => $pendingPayments,
            'completed_payments' => $completedPayments,
            'total_documents' => $totalDocuments,
            'documents_today' => $documentsToday,
        ];
    }
    
    /**
     * Show all shipments
     */
    public function shipments(Request $request)
    {
        $query = Shipment::with(['user', 'senderAddress', 'recipientAddress']);
        
        // Filters
        if ($request->filled('tracking_number')) {
            $query->where('tracking_number', 'LIKE', '%' . $request->tracking_number . '%');
        }
        
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('user_email')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('email', 'LIKE', '%' . $request->user_email . '%');
            });
        }
        
        $shipments = $query->latest()->paginate(20);
        
        $statuses = [
            'all' => 'All Statuses',
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'picked_up' => 'Picked Up',
            'in_transit' => 'In Transit',
            'customs_hold' => 'Customs Hold',
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
        ];
        
        return view('admin.shipments.index', compact('shipments', 'statuses'));
    }
    
    /**
     * Show shipment details
     */
    public function showShipment($id)
    {
        $shipment = Shipment::with([
            'user',
            'service',
            'senderAddress',
            'recipientAddress',
            'statusHistory',
            'documents',
            'payments',
            'customsDeclaration'
        ])->findOrFail($id);
        
        return view('admin.shipments.show', compact('shipment'));
    }
    
    /**
     * Update shipment status
     */
    public function updateShipmentStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,picked_up,in_transit,customs_hold,out_for_delivery,delivered,cancelled',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        DB::beginTransaction();
        
        try {
            $shipment = Shipment::findOrFail($id);
            
            // Update shipment status
            $shipment->status = $request->status;
            
            if ($request->filled('location')) {
                $shipment->current_location = $request->location;
            }
            
            $shipment->save();
            
            // Add to status history
            DB::table('shipment_status_history')->insert([
                'shipment_id' => $shipment->id,
                'status' => $request->status,
                'location' => $request->location ?? $shipment->current_location,
                'description' => $request->description ?? 'Status updated by admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Shipment status updated successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update shipment status: ' . $e->getMessage());
        }
    }
    
    /**
     * Show all users
     */
    public function users(Request $request)
    {
        $query = User::query();
        
        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%')
                  ->orWhere('phone', 'LIKE', '%' . $search . '%');
            });
        }
        
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }
        
        $users = $query->latest()->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Show user details
     */
    public function showUser($id)
    {
        $user = User::with([
            'addresses',
            'shipments' => function($q) {
                $q->latest()->limit(20);
            },
            'documents',
            'payments',
            'invoices',
            'cryptoPayments'
        ])->findOrFail($id);
        
        $userStats = [
            'total_shipments' => $user->shipments()->count(),
            'pending_shipments' => $user->shipments()->whereIn('status', ['pending', 'confirmed'])->count(),
            'delivered_shipments' => $user->shipments()->where('status', 'delivered')->count(),
            'total_documents' => $user->documents()->count(),
            'total_payments' => $user->payments()->count(),
            'pending_payments' => $user->payments()->where('status', 'pending')->count(),
            'total_invoices' => $user->invoices()->count(),
            'pending_invoices' => $user->invoices()->where('status', 'pending')->count(),
        ];
        
        return view('admin.users.show', compact('user', 'userStats'));
    }
    
    /**
     * Update user role
     */
    public function updateUserRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:user,admin',
        ]);
        
        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();
        
        return redirect()->back()->with('success', 'User role updated successfully!');
    }
    
    /**
     * Delete user
     */
    public function destroyUser($id)
    {
        // Prevent user from deleting themselves
        if (auth()-> auth::id() == $id) {
            return redirect()->route('admin.users')
                ->with('error', 'You cannot delete your own account.');
        }
        
        try {
            $user = User::findOrFail($id);
            
            // You might want to soft delete instead
            // $user->delete(); // This will cascade delete related records
            
            // Or deactivate instead of delete
            $user->delete(); // Or use soft delete if you have it
            
            return redirect()->route('admin.users')
                ->with('success', 'User deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.users')
                ->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }
    
    /**
     * Show all documents
     */
    public function documents(Request $request)
    {
        $query = Document::with(['user', 'shipment']);
        
        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('original_name', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'LIKE', '%' . $search . '%')
                         ->orWhere('email', 'LIKE', '%' . $search . '%');
                  });
            });
        }
        
        if ($request->filled('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        $documents = $query->latest()->paginate(15);
        
        // Get counts for stats
        $totalDocuments = Document::count();
        $pendingDocuments = Document::where('status', 'pending')->count();
        $verifiedDocuments = Document::where('status', 'verified')->count();
        
        return view('admin.documents.index', compact(
            'documents', 
            'totalDocuments',
            'pendingDocuments',
            'verifiedDocuments'
        ));
    }
    
    /**
     * Verify or reject document
     */
    public function verifyDocument(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $document = Document::findOrFail($id);
        
        // Update document status
        $document->status = $request->status;
        $document->verified_by = Auth::id();
        $document->verified_at = now();
        
        if ($request->filled('notes')) {
            $document->admin_notes = $request->notes;
        }
        
        $document->save();
        
        return redirect()->route('admin.documents')
            ->with('success', 'Document status updated successfully!');
    }
    
    /**
     * View document details
     */
    public function documentDetails($id)
    {
        $document = Document::with(['user', 'shipment'])->findOrFail($id);
        
        return view('admin.documents.show', compact('document'));
    }
    
    /**
     * View/download document file
     */
    public function viewDocument($id)
    {
        $document = Document::with(['user', 'shipment'])->findOrFail($id);
        
        // Check if file exists
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'Document not found in storage.');
        }
        
        $filePath = Storage::disk('public')->path($document->file_path);
        $mimeType = mime_content_type($filePath);
        
        // For images and PDFs, display in browser
        if (str_starts_with($mimeType, 'image/') || $mimeType === 'application/pdf') {
            return response()->file($filePath);
        }
        
        // For other files, force download
        return response()->download($filePath, $document->original_name);
    }
    
    /**
     * Show crypto payments (Enhanced Version)
     */
    public function cryptoPayments(Request $request)
    {
        $query = CryptoPayment::with(['user', 'invoice', 'verifier']);
        
        // Filters
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['pending', 'processing', 'confirmed']);
        }
        
        if ($request->filled('crypto_type') && $request->crypto_type !== '') {
            $query->where('crypto_type', $request->crypto_type);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'LIKE', '%' . $search . '%')
                  ->orWhere('payment_address', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('email', 'LIKE', '%' . $search . '%')
                         ->orWhere('name', 'LIKE', '%' . $search . '%');
                  })
                  ->orWhereHas('invoice', function($q3) use ($search) {
                      $q3->where('invoice_number', 'LIKE', '%' . $search . '%');
                  });
            });
        }
        
        $payments = $query->latest()->paginate(20);
        
        // Get stats for cards
        $pendingCount = CryptoPayment::where('status', 'pending')->count();
        $processingCount = CryptoPayment::where('status', 'processing')->count();
        $confirmedCount = CryptoPayment::where('status', 'confirmed')->count();
        $completedCount = CryptoPayment::where('status', 'completed')->count();
        $failedCount = CryptoPayment::whereIn('status', ['failed', 'expired'])->count();
        
        $statuses = [
            '' => 'All Active',
            'pending' => 'Pending',
            'processing' => 'Processing',
            'confirmed' => 'Confirmed',
            'completed' => 'Completed',
            'failed' => 'Failed',
            'expired' => 'Expired',
        ];
        
        $cryptoTypes = [
            '' => 'All Types',
            'BTC' => 'Bitcoin',
            'USDT_ERC20' => 'USDT (ERC20)',
            'USDT_TRC20' => 'USDT (TRC20)',
        ];
        
        return view('admin.payments.crypto', compact(
            'payments', 
            'statuses', 
            'cryptoTypes',
            'pendingCount',
            'processingCount',
            'confirmedCount',
            'completedCount',
            'failedCount'
        ));
    }
    
    /**
     * Update crypto payment status (Enhanced Version)
     */
    public function updateCryptoPayment(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,confirmed,completed,failed,expired',
            'admin_notes' => 'nullable|string',
            'confirmations' => 'nullable|integer|min:0',
        ]);
        
        DB::beginTransaction();
        
        try {
            $payment = CryptoPayment::findOrFail($id);
            
            // Update payment status
            $payment->status = $request->status;
            
            if ($request->filled('admin_notes')) {
                $payment->admin_notes = $request->admin_notes;
            }
            
            if ($request->filled('confirmations')) {
                $payment->confirmations = $request->confirmations;
            }
            
            // Set timestamps based on status
            if ($request->status === 'confirmed' || $request->status === 'completed') {
                if (!$payment->verified_by) {
                    $payment->verified_by = Auth::id();
                    $payment->verified_at = now();
                }
                
                if ($request->status === 'confirmed' && !$payment->confirmed_at) {
                    $payment->confirmed_at = now();
                }
            }
            
            // If marking as paid, set paid_at
            if (($request->status === 'confirmed' || $request->status === 'completed') && !$payment->paid_at) {
                $payment->paid_at = now();
            }
            
            $payment->save();
            
            // If payment is completed, also update invoice status
            if ($request->status === 'completed' && $payment->invoice) {
                $payment->invoice->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Payment status updated successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update payment status: ' . $e->getMessage());
        }
    }
    
    /**
     * Export crypto payments
     */
    public function exportCryptoPayments(Request $request)
    {
        $query = CryptoPayment::with(['user', 'invoice']);
        
        // Apply same filters as listing page
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('crypto_type') && $request->crypto_type !== '') {
            $query->where('crypto_type', $request->crypto_type);
        }
        
        $payments = $query->latest()->get();
        
        // For now, return a simple CSV (you can install Laravel Excel package for better exports)
        $fileName = 'crypto_payments_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];
        
        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID',
                'Invoice Number',
                'User Name',
                'User Email',
                'Crypto Type',
                'Crypto Amount',
                'USDT Amount',
                'USD Amount',
                'Payment Address',
                'Transaction ID',
                'Status',
                'Confirmations',
                'Exchange Rate',
                'Created At',
                'Paid At',
                'Confirmed At',
                'Admin Notes'
            ]);
            
            // Add data rows
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->invoice->invoice_number ?? 'N/A',
                    $payment->user->name ?? 'N/A',
                    $payment->user->email ?? 'N/A',
                    $payment->crypto_type,
                    $payment->crypto_amount ?? '0',
                    $payment->usdt_amount ?? '0',
                    $payment->usdt_amount ?? '0', // USD amount (same as USDT for now)
                    $payment->payment_address,
                    $payment->transaction_id ?? 'N/A',
                    $payment->status,
                    $payment->confirmations,
                    $payment->exchange_rate ?? '0',
                    $payment->created_at->format('Y-m-d H:i:s'),
                    $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i:s') : 'N/A',
                    $payment->confirmed_at ? $payment->confirmed_at->format('Y-m-d H:i:s') : 'N/A',
                    $payment->admin_notes ?? ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Show wallet management page
     */
    public function wallets()
    {
        // Get all wallets with pagination
        $wallets = CryptoAddress::with('creator')->latest()->paginate(10);
        
        // Calculate statistics
        $totalWallets = CryptoAddress::count();
        $activeWallets = CryptoAddress::where('is_active', true)->count();
        $btcWallets = CryptoAddress::where('crypto_type', 'BTC')->count();
        $usdtWallets = CryptoAddress::whereIn('crypto_type', ['USDT_ERC20', 'USDT_TRC20'])->count();
        
        return view('admin.wallets.index', compact(
            'wallets',
            'totalWallets',
            'activeWallets',
            'btcWallets',
            'usdtWallets'
        ));
    }
    
    /**
     * Store new wallet
     */
    public function storeWallet(Request $request)
    {
        $validated = $request->validate([
            'crypto_type' => 'required|in:BTC,USDT_ERC20,USDT_TRC20',
            'address' => 'required|string|max:255|unique:crypto_addresses,address',
            'label' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $validated['created_by'] = Auth::id();
        $validated['is_active'] = $request->boolean('is_active', true);
        
        CryptoAddress::create($validated);
        
        return redirect()->route('admin.wallets')
            ->with('success', 'Wallet address added successfully!');
    }
    
    /**
     * Show edit wallet form
     */
    public function editWallet($id)
    {
        $wallet = CryptoAddress::findOrFail($id);
        
        return view('admin.wallets.edit', compact('wallet'));
    }
    
    /**
     * Update wallet
     */
    public function updateWallet(Request $request, $id)
    {
        $wallet = CryptoAddress::findOrFail($id);
        
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_active'] = $request->boolean('is_active', false);
        
        $wallet->update($validated);
        
        return redirect()->route('admin.wallets')
            ->with('success', 'Wallet updated successfully!');
    }
    
    /**
     * Delete wallet
     */
    public function destroyWallet($id)
    {
        $wallet = CryptoAddress::findOrFail($id);
        
        // Check if wallet has been used
        $used = CryptoPayment::where('payment_address', $wallet->address)->exists();
        
        if ($used) {
            return redirect()->route('admin.wallets')
                ->with('error', 'Cannot delete wallet that has been used for payments.');
        }
        
        $wallet->delete();
        
        return redirect()->route('admin.wallets')
            ->with('success', 'Wallet deleted successfully!');
    }
    
    /**
     * Manage crypto addresses (legacy method - can be removed)
     */
    public function cryptoAddresses()
    {
        $addresses = CryptoAddress::with('creator')
            ->latest()
            ->get();
            
        $stats = [
            'total_addresses' => CryptoAddress::count(),
            'active_addresses' => CryptoAddress::where('is_active', true)->count(),
            'btc_addresses' => CryptoAddress::where('crypto_type', 'BTC')->count(),
            'usdt_erc20_addresses' => CryptoAddress::where('crypto_type', 'USDT_ERC20')->count(),
            'usdt_trc20_addresses' => CryptoAddress::where('crypto_type', 'USDT_TRC20')->count(),
        ];
        
        return view('admin.crypto.addresses', compact('addresses', 'stats'));
    }
    
    /**
     * Add new crypto address (legacy method - can be removed)
     */
    public function addCryptoAddress(Request $request)
    {
        $request->validate([
            'crypto_type' => 'required|in:BTC,USDT_ERC20,USDT_TRC20',
            'address' => 'required|string|max:255|unique:crypto_addresses,address',
            'label' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        
        CryptoAddress::create([
            'crypto_type' => $request->crypto_type,
            'address' => $request->address,
            'label' => $request->label,
            'is_active' => true,
            'created_by' => Auth::id(),
            'notes' => $request->notes,
        ]);
        
        return redirect()->back()->with('success', 'Crypto address added successfully!');
    }
    

    /**
     * Show analytics/reports
     */
    public function analytics(Request $request)
    {
        // Date range
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->start_date)
            : Carbon::now()->subDays(30);
        
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)
            : Carbon::now();
        
        // User analytics
        $userCount = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $previousUserCount = User::whereBetween('created_at', [$startDate->copy()->subDays(30), $endDate->copy()->subDays(30)])->count();
        $userGrowth = $previousUserCount > 0 ? round((($userCount - $previousUserCount) / $previousUserCount) * 100, 2) : 0;
        
        // Shipment analytics
        $shipmentCount = Shipment::whereBetween('created_at', [$startDate, $endDate])->count();
        $previousShipmentCount = Shipment::whereBetween('created_at', [$startDate->copy()->subDays(30), $endDate->copy()->subDays(30)])->count();
        $shipmentGrowth = $previousShipmentCount > 0 ? round((($shipmentCount - $previousShipmentCount) / $previousShipmentCount) * 100, 2) : 0;
        
        // Revenue analytics
        $revenue = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');
        
        $previousRevenue = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$startDate->copy()->subDays(30), $endDate->copy()->subDays(30)])
            ->sum('amount');
        
        $revenueGrowth = $previousRevenue > 0 ? round((($revenue - $previousRevenue) / $previousRevenue) * 100, 2) : 0;
        
        // Document analytics
        $documentCount = Document::whereBetween('created_at', [$startDate, $endDate])->count();
        $previousDocumentCount = Document::whereBetween('created_at', [$startDate->copy()->subDays(30), $endDate->copy()->subDays(30)])->count();
        $documentGrowth = $previousDocumentCount > 0 ? round((($documentCount - $previousDocumentCount) / $previousDocumentCount) * 100, 2) : 0;
        
        // Chart data
        $shipmentData = [];
        $shipmentLabels = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $count = Shipment::whereDate('created_at', $currentDate)->count();
            $shipmentData[] = $count;
            $shipmentLabels[] = $currentDate->format('M d');
            $currentDate->addDay();
        }
        
        // Status data
        $statusData = [
            Shipment::where('status', 'pending')->orWhere('status', 'confirmed')->count(),
            Shipment::whereIn('status', ['picked_up', 'in_transit', 'customs_hold', 'out_for_delivery'])->count(),
            Shipment::where('status', 'delivered')->count(),
            Shipment::where('status', 'cancelled')->count(),
        ];
        
        // Top users
        $topUsers = User::withCount(['shipments'])
            ->withSum('payments', 'amount')
            ->orderBy('shipments_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function($user) {
                $user->delivered_count = $user->shipments()->where('status', 'delivered')->count();
                $user->total_spent = $user->payments()->where('status', 'completed')->sum('amount');
                return $user;
            });
        
        // REAL Recent Activity from database
        $recentActivity = collect();
        
        // Get recent shipments (last 10)
        $recentShipments = Shipment::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($shipment) {
                return [
                    'type' => 'shipment',
                    'title' => 'New Shipment Created',
                    'time' => $shipment->created_at->diffForHumans(),
                    'description' => "Tracking: {$shipment->tracking_number}",
                    'user_name' => $shipment->user->name ?? 'N/A',
                    'status' => $shipment->status,
                    'created_at' => $shipment->created_at
                ];
            });
        
        // Get recent users (last 10)
        $recentUsers = User::whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'user',
                    'title' => 'New User Registered',
                    'time' => $user->created_at->diffForHumans(),
                    'description' => $user->email,
                    'user_name' => $user->name,
                    'role' => $user->role,
                    'created_at' => $user->created_at
                ];
            });
        
        // Get recent payments (last 10)
        $recentPayments = Payment::with('user')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($payment) {
                return [
                    'type' => 'payment',
                    'title' => 'Payment Received',
                    'time' => $payment->created_at->diffForHumans(),
                    'description' => "Amount: $" . number_format($payment->amount, 2),
                    'user_name' => $payment->user->name ?? 'N/A',
                    'payment_method' => $payment->payment_method,
                    'created_at' => $payment->created_at
                ];
            });
        
        // Get recent documents (last 10)
        $recentDocuments = Document::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($document) {
                return [
                    'type' => 'document',
                    'title' => 'Document Uploaded',
                    'time' => $document->created_at->diffForHumans(),
                    'description' => $document->original_name,
                    'user_name' => $document->user->name ?? 'N/A',
                    'document_type' => $document->type,
                    'status' => $document->status,
                    'created_at' => $document->created_at
                ];
            });
        
        // Get recent crypto payments (last 10)
        $recentCryptoPayments = CryptoPayment::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($payment) {
                return [
                    'type' => 'crypto',
                    'title' => 'Crypto Payment',
                    'time' => $payment->created_at->diffForHumans(),
                    'description' => "{$payment->crypto_type}: $" . number_format($payment->usd_amount, 2),
                    'user_name' => $payment->user->name ?? 'N/A',
                    'status' => $payment->status,
                    'created_at' => $payment->created_at
                ];
            });
        
        // Get shipment status updates (last 10)
        $shipmentStatusUpdates = DB::table('shipment_status_history')
            ->join('shipments', 'shipment_status_history.shipment_id', '=', 'shipments.id')
            ->whereBetween('shipment_status_history.created_at', [$startDate, $endDate])
            ->select('shipment_status_history.*', 'shipments.tracking_number')
            ->latest('shipment_status_history.created_at')
            ->limit(10)
            ->get()
            ->map(function ($update) {
                return [
                    'type' => 'status_update',
                    'title' => 'Shipment Status Updated',
                    'time' => Carbon::parse($update->created_at)->diffForHumans(),
                    'description' => "Tracking: {$update->tracking_number} → " . ucfirst(str_replace('_', ' ', $update->status)),
                    'status' => $update->status,
                    'location' => $update->location,
                    'created_at' => $update->created_at
                ];
            });
        
        // Combine all activities
        $recentActivity = $recentActivity
            ->merge($recentShipments)
            ->merge($recentUsers)
            ->merge($recentPayments)
            ->merge($recentDocuments)
            ->merge($recentCryptoPayments)
            ->merge($shipmentStatusUpdates)
            ->sortByDesc('created_at') // Sort by most recent
            ->take(10) // Take only the 10 most recent
            ->values(); // Reset array keys
    
        return view('admin.analytics.index', compact(
            'userCount',
            'userGrowth',
            'shipmentCount',
            'shipmentGrowth',
            'revenue',
            'revenueGrowth',
            'documentCount',
            'documentGrowth',
            'shipmentData',
            'shipmentLabels',
            'statusData',
            'topUsers',
            'recentActivity',
            'startDate',
            'endDate'
        ));
    }
    
    /**
     * Show crypto payment details
     */
    public function showCryptoPayment($id)
    {
        $payment = CryptoPayment::with(['user', 'invoice', 'verifier'])->findOrFail($id);
        
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show settings page
     */
    public function settings()
    {
        return view('admin.settings.index');
    }
    
    /**
     * Show contact messages
     */
    public function contactMessages()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(20);
        $unreadCount = ContactMessage::where('status', 'unread')->count();
        
        return view('admin.contact-messages.index', compact('messages', 'unreadCount'));
    }
    
    /**
     * Show contact message details
     */
    public function showContactMessage($id)
    {
        $message = ContactMessage::findOrFail($id);
        
        // Mark as read if currently unread
        if ($message->status === 'unread') {
            $message->update(['status' => 'read']);
        }
        
        return view('admin.contact-messages.show', compact('message'));
    }
    
    /**
     * Update contact message status
     */
    public function updateContactMessageStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:read,replied,archived',
            'admin_notes' => 'nullable|string|max:1000'
        ]);
        
        $message = ContactMessage::findOrFail($id);
        
        $updateData = ['status' => $request->status];
        
        if ($request->status === 'replied') {
            $updateData['replied_at'] = now();
        }
        
        if ($request->filled('admin_notes')) {
            $updateData['admin_notes'] = $request->admin_notes;
        }
        
        $message->update($updateData);
        
        return redirect()->back()->with('success', 'Message status updated successfully.');
    }
    
    /**
     * Delete contact message
     */
    public function deleteContactMessage($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();
        
        return redirect()->route('admin.contact-messages.index')->with('success', 'Message deleted successfully.');
    }
}