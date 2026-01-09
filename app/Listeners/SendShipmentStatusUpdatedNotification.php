<?php

namespace App\Listeners;

use App\Events\ShipmentStatusUpdated;
use App\Notifications\ShipmentStatusUpdated as ShipmentStatusUpdatedNotification;
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
        
        // Check if user wants notifications for this status change
        $preferences = $user->notification_preferences;
        
        // Map status to preference key
        $statusPreferences = [
            'out_for_delivery' => 'shipment_out_for_delivery',
            'delivered' => 'shipment_delivered',
            'customs_hold' => 'shipment_customs_hold',
            'cancelled' => 'shipment_cancelled',
        ];
        
        $preferenceKey = $statusPreferences[$event->newStatus] ?? 'shipment_status_updated';
        
        if ($preferences[$preferenceKey] ?? true) {
            $user->notify(new ShipmentStatusUpdatedNotification(
                $event->shipment,
                $event->oldStatus,
                $event->newStatus
            ));
        }
    }
}