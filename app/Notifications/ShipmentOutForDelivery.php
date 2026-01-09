<?php

namespace App\Notifications;

use App\Models\Shipment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShipmentOutForDelivery extends Notification implements ShouldQueue
{
    use Queueable;

    public $shipment;

    public function __construct(Shipment $shipment)
    {
        $this->shipment = $shipment;
    }

    public function via(object $notifiable): array
    {
        $channels = ['database'];
        
        if ($notifiable->notification_preferences['email_notifications'] ?? true) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🚚 Shipment Out for Delivery!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! Your shipment is out for delivery today.')
            ->line('Tracking Number: **' . $this->shipment->tracking_number . '**')
            ->line('Please ensure someone is available to receive the package.')
            ->action('Track Delivery', route('shipments.show', $this->shipment))
            ->line('Estimated Delivery: ' . ($this->shipment->estimated_delivery ? $this->shipment->estimated_delivery->format('F j, Y') : 'Today'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Out for Delivery 🚚',
            'message' => 'Your shipment ' . $this->shipment->tracking_number . ' is out for delivery today!',
            'type' => 'shipment_out_for_delivery',
            'category' => 'shipment',
            'priority' => 'high',
            'shipment_id' => $this->shipment->id,
            'tracking_number' => $this->shipment->tracking_number,
            'url' => route('shipments.show', $this->shipment),
            'icon' => 'ri-truck-line',
            'color' => 'warning',
            'estimated_delivery' => $this->shipment->estimated_delivery,
        ];
    }
}