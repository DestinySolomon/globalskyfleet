<?php

namespace App\Listeners;

use App\Events\ShipmentStatusUpdated;
use App\Notifications\ShipmentStatusUpdated as ShipmentStatusUpdatedNotification;
use App\Notifications\ShipmentNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendShipmentStatusUpdatedNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(ShipmentStatusUpdated $event): void
    {
        $user = $event->shipment->user;
        $shipment = $event->shipment;
        $oldStatus = $event->oldStatus;
        $newStatus = $event->newStatus;
        
        // 1. Send notification to the shipment owner
        $preferences = $user->notification_preferences;
        
        // Map status to preference key
        $statusPreferences = [
            'out_for_delivery' => 'shipment_out_for_delivery',
            'delivered' => 'shipment_delivered',
            'customs_hold' => 'shipment_customs_hold',
            'cancelled' => 'shipment_cancelled',
        ];
        
        $preferenceKey = $statusPreferences[$newStatus] ?? 'shipment_status_updated';
        
        if ($preferences[$preferenceKey] ?? true) {
            $user->notify(new ShipmentStatusUpdatedNotification(
                $shipment,
                $oldStatus,
                $newStatus
            ));
        }
        
        // 2. Send notification to ALL admins for important status changes
        $importantStatuses = ['pending', 'confirmed', 'picked_up', 'in_transit', 'customs_hold', 'out_for_delivery', 'delivered', 'cancelled', 'returned'];
        
        if (in_array($newStatus, $importantStatuses)) {
            $admins = User::whereIn('role', ['admin', 'super_admin'])->get();
            
            if ($admins->isNotEmpty()) {
                $statusLabels = [
                    'pending' => 'Pending',
                    'confirmed' => 'Confirmed',
                    'picked_up' => 'Picked Up',
                    'in_transit' => 'In Transit',
                    'customs_hold' => 'Customs Hold',
                    'out_for_delivery' => 'Out for Delivery',
                    'delivered' => 'Delivered',
                    'cancelled' => 'Cancelled',
                    'returned' => 'Returned',
                ];
                
                $statusLabel = $statusLabels[$newStatus] ?? ucfirst($newStatus);
                $oldStatusLabel = $statusLabels[$oldStatus] ?? ucfirst($oldStatus);
                
                // Determine priority
                $priority = 'normal';
                if (in_array($newStatus, ['customs_hold', 'cancelled'])) {
                    $priority = 'high';
                } elseif (in_array($newStatus, ['out_for_delivery', 'delivered'])) {
                    $priority = 'normal';
                }
                
                Notification::send($admins, new ShipmentNotification(
                    $shipment,
                    'Shipment Status Update: ' . $statusLabel,
                    'Shipment #' . $shipment->tracking_number . ' status changed from ' . $oldStatusLabel . ' to ' . $statusLabel . '. Customer: ' . $user->name,
                    $priority
                ));
            }
        }
    }
}