<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get shipment statistics
        $stats = [
            'total' => $user->shipments()->count(),
            'pending' => $user->shipments()->whereIn('status', ['pending', 'confirmed'])->count(),
            'in_transit' => $user->shipments()->whereIn('status', ['picked_up', 'in_transit', 'customs_hold', 'out_for_delivery'])->count(),
            'delivered' => $user->shipments()->where('status', 'delivered')->count(),
        ];
        
        // Get recent shipments (you'll need to create this method in User model)
        $recentShipments = $user->shipments()
            ->with(['recipientAddress'])
            ->latest()
            ->limit(5)
            ->get();
        
        return view('pages.dashboard', compact('stats', 'recentShipments'));
    }
}