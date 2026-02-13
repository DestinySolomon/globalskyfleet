<?php

namespace App\Listeners;

use App\Events\ShipmentCreated;
use App\Notifications\ShipmentCreated as ShipmentCreatedNotification;
use App\Notifications\ShipmentNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
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
        $shipment = $event->shipment;
        
        // 1. Send notification to the user who created the shipment
        if ($user->notification_preferences['shipment_created'] ?? true) {
            $user->notify(new ShipmentCreatedNotification($shipment));
        }
        
        // 2. Send notification to ALL admins (super_admin and admin)
        $admins = User::whereIn('role', ['admin', 'super_admin'])->get();
        
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new ShipmentNotification(
                $shipment,
                'New Shipment Created',
                'New shipment created by ' . $user->name . '. Tracking #: ' . $shipment->tracking_number,
                'normal'
            ));
        }
    }
}