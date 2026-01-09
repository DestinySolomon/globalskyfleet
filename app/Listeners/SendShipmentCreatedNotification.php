<?php

namespace App\Listeners;

use App\Events\ShipmentCreated;
use App\Notifications\ShipmentCreated as ShipmentCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendShipmentCreatedNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(ShipmentCreated $event): void
    {
        $user = $event->shipment->user;
        
        // Check if user wants shipment creation notifications
        if ($user->notification_preferences['shipment_created'] ?? true) {
            $user->notify(new ShipmentCreatedNotification($event->shipment));
        }
    }
}