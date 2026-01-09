<?php

namespace App\Notifications;

use App\Models\Shipment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShipmentCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public $shipment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Shipment $shipment)
    {
        $this->shipment = $shipment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Check user preferences
        $channels = ['database'];
        
        if ($notifiable->notification_preferences['email_notifications'] ?? true) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Shipment Created - Tracking #' . $this->shipment->tracking_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your shipment has been created successfully.')
            ->line('Tracking Number: **' . $this->shipment->tracking_number . '**')
            ->line('Status: ' . ucfirst(str_replace('_', ' ', $this->shipment->status)))
            ->action('Track Shipment', route('shipments.show', $this->shipment))
            ->line('Thank you for using our service!');
    }

    /**
     * Get the array representation for database.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Shipment Created',
            'message' => 'Your shipment has been created. Tracking #: ' . $this->shipment->tracking_number,
            'type' => 'shipment_created',
            'category' => 'shipment',
            'priority' => 'normal',
            'shipment_id' => $this->shipment->id,
            'tracking_number' => $this->shipment->tracking_number,
            'status' => $this->shipment->status,
            'url' => route('shipments.show', $this->shipment),
            'icon' => 'ri-ship-line',
            'color' => 'primary',
        ];
    }
}