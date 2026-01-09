<?php

namespace App\Notifications;

use App\Models\Shipment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShipmentStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public $shipment;
    public $oldStatus;
    public $newStatus;

    public function __construct(Shipment $shipment, string $oldStatus, string $newStatus)
    {
        $this->shipment = $shipment;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
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

        return (new MailMessage)
            ->subject('Shipment Status Update - ' . ($statusLabels[$this->newStatus] ?? $this->newStatus))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your shipment status has been updated.')
            ->line('Tracking Number: **' . $this->shipment->tracking_number . '**')
            ->line('Old Status: ' . ($statusLabels[$this->oldStatus] ?? $this->oldStatus))
            ->line('New Status: **' . ($statusLabels[$this->newStatus] ?? $this->newStatus) . '**')
            ->action('View Details', route('shipments.show', $this->shipment))
            ->line('Thank you for using our service!');
    }

    public function toArray(object $notifiable): array
    {
        $statusLabels = [
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Delivered',
            'customs_hold' => 'Customs Hold',
            'cancelled' => 'Cancelled',
        ];

        $message = 'Your shipment ' . $this->shipment->tracking_number . ' status changed';
        if (isset($statusLabels[$this->newStatus])) {
            $message = 'Your shipment ' . $this->shipment->tracking_number . ' is now ' . $statusLabels[$this->newStatus];
        }

        return [
            'title' => 'Status Updated: ' . ucfirst(str_replace('_', ' ', $this->newStatus)),
            'message' => $message,
            'type' => 'shipment_status_updated',
            'category' => 'shipment',
            'priority' => $this->newStatus === 'out_for_delivery' ? 'high' : 'normal',
            'shipment_id' => $this->shipment->id,
            'tracking_number' => $this->shipment->tracking_number,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'url' => route('shipments.show', $this->shipment),
            'icon' => $this->getStatusIcon($this->newStatus),
            'color' => $this->getStatusColor($this->newStatus),
        ];
    }

    private function getStatusIcon(string $status): string
    {
        $icons = [
            'out_for_delivery' => 'ri-truck-line',
            'delivered' => 'ri-checkbox-circle-line',
            'customs_hold' => 'ri-alert-line',
            'cancelled' => 'ri-close-circle-line',
            'default' => 'ri-information-line',
        ];

        return $icons[$status] ?? $icons['default'];
    }

    private function getStatusColor(string $status): string
    {
        $colors = [
            'out_for_delivery' => 'warning',
            'delivered' => 'success',
            'customs_hold' => 'danger',
            'cancelled' => 'danger',
            'default' => 'primary',
        ];

        return $colors[$status] ?? $colors['default'];
    }
}