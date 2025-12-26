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
        // Validate the form
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|in:general,quote,tracking,partnership,complaint,other',
            'message' => 'required|string|max:2000'
        ]);
        
        // In a real application, you would:
        // 1. Save the contact form submission to the database
        // 2. Send email notifications to the support team
        // 3. Send a confirmation email to the user
        // 4. Maybe create a ticket in your support system
        
        // For now, we'll just flash a success message
        $request->session()->flash('success', true);
        
        // Store the contact submission in session for demo purposes
        $request->session()->flash('contact_data', $validated);
        
        return redirect()->route('contact');
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
}