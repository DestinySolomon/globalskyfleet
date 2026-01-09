<?php

namespace App\Notifications;

use App\Models\Shipment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShipmentDelivered extends Notification implements ShouldQueue
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
            ->subject(' Shipment Delivered Successfully!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! Your shipment has been delivered.')
            ->line('Tracking Number: **' . $this->shipment->tracking_number . '**')
            ->line('Delivery Date: ' . ($this->shipment->actual_delivery ? $this->shipment->actual_delivery->format('F j, Y \a\t g:i A') : now()->format('F j, Y')))
            ->action('View Details', route('shipments.show', $this->shipment))
            ->line('Thank you for choosing our service!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Delivered Successfully ✅',
            'message' => 'Your shipment ' . $this->shipment->tracking_number . ' has been delivered.',
            'type' => 'shipment_delivered',
            'category' => 'shipment',
            'priority' => 'normal',
            'shipment_id' => $this->shipment->id,
            'tracking_number' => $this->shipment->tracking_number,
            'url' => route('shipments.show', $this->shipment),
            'icon' => 'ri-checkbox-circle-line',
            'color' => 'success',
            'delivered_at' => $this->shipment->actual_delivery ?? now(),
        ];
    }
}