<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Invoice;
use App\Models\CryptoPayment;
use App\Models\CryptoAddress;
use App\Models\ExchangeRate;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\ExchangeRateService;
use Illuminate\Support\Facades\Http;

class BillingController extends Controller
{
    /**
     * Display the main billing dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get billing statistics
        $stats = [
            'total_invoices' => $user->invoices()->count(),
            'pending_invoices' => $user->invoices()->where('status', 'pending')->count(),
            'overdue_invoices' => $user->invoices()->where('status', 'pending')
                ->where('due_date', '<', now())
                ->count(),
            'total_paid' => $user->invoices()->where('status', 'paid')->sum('amount'),
            'balance_due' => $user->invoices()->where('status', 'pending')->sum('amount'),
        ];
        
        // Get recent invoices
        $recentInvoices = $user->invoices()
            ->latest()
            ->take(5)
            ->get();
        
        // Get recent payments
        $recentPayments = $user->cryptoPayments()
            ->latest()
            ->take(5)
            ->get();
        
        return view('billing.index', compact('stats', 'recentInvoices', 'recentPayments'));
    }
    
    /**
     * Display all invoices
     */
    public function invoices()
    {
        $user = Auth::user();
        $invoices = $user->invoices()
            ->latest()
            ->paginate(10);
        
        return view('billing.invoices', compact('invoices'));
    }
    
    /**
     * Display a single invoice
     */
    public function showInvoice(Invoice $invoice)
    {
        // Check if user owns this invoice
        if ($invoice->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        return view('billing.invoice-show', compact('invoice'));
    }
    
    /**
     * Download invoice as PDF
     */
    public function downloadInvoice(Invoice $invoice)
    {
        // Check if user owns this invoice
        if ($invoice->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        // For now, return a simple text response instead of PDF
        return response()->streamDownload(function () use ($invoice) {
            echo "Invoice #{$invoice->invoice_number}\n";
            echo "==============================\n";
            echo "Date: {$invoice->invoice_date->format('F d, Y')}\n";
            echo "Due Date: {$invoice->due_date->format('F d, Y')}\n";
            echo "Amount: {$invoice->formatted_amount}\n";
            echo "Status: " . ucfirst($invoice->status) . "\n";
            echo "Description: {$invoice->description}\n";
            echo "\nThank you for your business!\n";
        }, "invoice-{$invoice->invoice_number}.txt");
    }
    
    /**
     * Show payment page for an invoice
     * 
     */


public function payInvoice(Invoice $invoice)
{
    // Check if user owns this invoice
    if ($invoice->user_id !== Auth::id()) {
        abort(403, 'Unauthorized');
    }
    
    // Check if invoice is already paid
    if ($invoice->status === 'paid') {
        return redirect()->route('billing.invoices.show', $invoice)
            ->with('info', 'This invoice has already been paid.');
    }
    
    // Get available crypto addresses
    $btcAddress = CryptoAddress::where('crypto_type', 'BTC')
        ->where('is_active', true)
        ->first();
        
    $usdtErc20Address = CryptoAddress::where('crypto_type', 'USDT_ERC20')
        ->where('is_active', true)
        ->first();
        
    $usdtTrc20Address = CryptoAddress::where('crypto_type', 'USDT_TRC20')
        ->where('is_active', true)
        ->first();
    
    // Get real-time exchange rates
    $exchangeService = new ExchangeRateService();
    
    $btcRate = $exchangeService->getBtcRate();
    $usdtRate = $exchangeService->getUsdtRate();
    
    // Calculate crypto amounts
    $btcAmount = $btcRate ? $invoice->amount / $btcRate : null;
    $usdtAmount = $usdtRate ? $invoice->amount / $usdtRate : null;
    
    // Format rates for display
    $formattedBtcRate = $btcRate ? number_format($btcRate, 2) : 'N/A';
    $formattedUsdtRate = $usdtRate ? number_format($usdtRate, 4) : 'N/A';
    
    return view('billing.pay', compact(
        'invoice', 
        'btcAddress', 
        'usdtErc20Address', 
        'usdtTrc20Address',
        'btcRate',
        'usdtRate',
        'formattedBtcRate',
        'formattedUsdtRate',
        'btcAmount',
        'usdtAmount'
    ));
}

/**
 * Get current exchange rates (AJAX endpoint)
 */
public function getRates()
{
    $exchangeService = new ExchangeRateService();
    
    return response()->json([
        'success' => true,
        'rates' => $exchangeService->getAllRates(),
        'updated_at' => now()->toISOString(),
    ]);
}

    
    /**
     * Submit payment proof for an invoice
     */
    public function submitPayment(Request $request, Invoice $invoice)
    {
        // Check if user owns this invoice
        if ($invoice->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        // Validate request
        $request->validate([
            'crypto_type' => 'required|in:BTC,USDT_ERC20,USDT_TRC20',
            'transaction_id' => 'required|string|max:255',
            'payment_proof' => 'nullable|image|max:2048',
        ]);
        
        // Get the crypto address for this type
        $cryptoAddress = CryptoAddress::where('crypto_type', $request->crypto_type)
            ->where('is_active', true)
            ->first();
        
        if (!$cryptoAddress) {
            return back()->with('error', 'Payment method is currently unavailable.');
        }
        
        // Handle file upload
        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
        }
        
        // Get exchange rate
        $rate = ExchangeRate::getLatestRate(
            $request->crypto_type === 'BTC' ? 'BTC' : 'USDT'
        );
        
        // Create payment record
        $payment = CryptoPayment::create([
            'invoice_id' => $invoice->id,
            'user_id' => Auth::id(),
            'crypto_type' => $request->crypto_type,
            'crypto_amount' => $request->crypto_type === 'BTC' ? 
                ($rate ? $invoice->amount / $rate : null) : null,
            'usdt_amount' => $request->crypto_type !== 'BTC' ? 
                ($rate ? $invoice->amount / $rate : null) : null,
            'payment_address' => $cryptoAddress->address,
            'transaction_id' => $request->transaction_id,
            'payment_proof' => $proofPath,
            'exchange_rate' => $rate,
            'status' => 'pending',
            'expires_at' => now()->addHours(24), // Payment expires in 24 hours
        ]);
        
        // Increment address usage
        $cryptoAddress->incrementUsage();
        
        return redirect()->route('billing.payments')
            ->with('success', 'Payment submitted successfully. It will be verified within 24 hours.');
    }
    
    /**
     * Display payment history
     */
    public function payments()
    {
        $user = Auth::user();
        $payments = $user->cryptoPayments()
            ->with('invoice')
            ->latest()
            ->paginate(10);
        
        return view('billing.payments', compact('payments'));
    }
    
    /**
     * Upload additional payment proof
     */
    public function uploadProof(Request $request, CryptoPayment $payment)
    {
        // Check if user owns this payment
        if ($payment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'payment_proof' => 'required|image|max:2048',
        ]);
        
        // Delete old proof if exists
        if ($payment->payment_proof) {
            Storage::disk('public')->delete($payment->payment_proof);
        }
        
        // Upload new proof
        $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
        
        $payment->update([
            'payment_proof' => $proofPath,
        ]);
        
        return back()->with('success', 'Payment proof updated successfully.');
    }
    
    /**
     * Display billing settings
     */
    public function settings()
    {
        return view('billing.settings');
    }
    
    /**
     * Export payments as CSV
     */
    public function exportPayments(Request $request)
    {
        $user = Auth::user();
        
        $payments = $user->cryptoPayments()
            ->with('invoice')
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->method, function($query, $method) {
                return $query->where('crypto_type', $method);
            })
            ->when($request->date_from, function($query, $dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($request->date_to, function($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            })
            ->get();
        
        $filename = 'payments-export-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($file, [
                'Date',
                'Invoice Number',
                'Payment Method',
                'Crypto Amount',
                'USD Amount',
                'Transaction ID',
                'Status',
                'Paid At',
                'Confirmed At'
            ]);
            
            // Data
            foreach ($payments as $payment) {
                $usdAmount = $payment->crypto_type === 'BTC' 
                    ? ($payment->crypto_amount * ($payment->exchange_rate ?? 0))
                    : ($payment->usdt_amount * ($payment->exchange_rate ?? 0));
                
                fputcsv($file, [
                    $payment->created_at->format('Y-m-d H:i:s'),
                    $payment->invoice->invoice_number ?? 'N/A',
                    $payment->crypto_type,
                    $payment->crypto_type === 'BTC' ? $payment->crypto_amount : $payment->usdt_amount,
                    number_format($usdAmount, 2),
                    $payment->transaction_id,
                    $payment->status,
                    $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i:s') : '',
                    $payment->confirmed_at ? $payment->confirmed_at->format('Y-m-d H:i:s') : ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Get payment details for modal (AJAX)
     */
    public function paymentDetails(CryptoPayment $payment)
    {
        // Check if user owns this payment
        if ($payment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false, 
                'message' => 'Unauthorized access'
            ], 403);
        }
        
        // Return HTML for the modal
        $html = view('billing.partials.payment-details', compact('payment'))->render();
        
        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }
}