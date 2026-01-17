<?php

namespace App\Notifications;

use App\Models\Payment;
use App\Models\CryptoPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class PaymentNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    public $payment;
    public $title;
    public $message;
    public $priority;
    public $isCrypto;

    /**
     * Create a new notification instance.
     */
    public function __construct($payment, $title, $message, $priority = 'normal')
    {
        $this->payment = $payment;
        $this->title = $title;
        $this->message = $message;
        $this->priority = $priority;
        $this->isCrypto = $payment instanceof CryptoPayment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];
        
        // Add email if enabled
        if ($notifiable->notification_preferences['email_notifications'] ?? true) {
            $channels[] = 'mail';
        }
        
        // Add broadcast for real-time
        if ($notifiable->notification_preferences['push_notifications'] ?? true) {
            $channels[] = 'broadcast';
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->title)
            ->line($this->message);
        
        if ($this->isCrypto) {
            $mail->action('View Crypto Payment', route('admin.payments.crypto.show', $this->payment->id))
                ->line('Amount: $' . number_format($this->payment->usd_amount, 2))
                ->line('Status: ' . ucfirst($this->payment->status));
        } else {
            // Add your regular payment route here
            $mail->action('View Payment', route('admin.payments.show', $this->payment->id))
                ->line('Amount: $' . number_format($this->payment->amount, 2))
                ->line('Status: ' . ucfirst($this->payment->status));
        }
        
        return $mail;
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'type' => get_class($this),
            'data' => $this->toArray($notifiable),
            'read_at' => null,
            'created_at' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $data = [
            'title' => $this->title,
            'message' => $this->message,
            'category' => 'payment',
            'priority' => $this->priority,
            'status' => $this->payment->status,
        ];

        if ($this->isCrypto) {
            $data['icon'] = 'ri-bit-coin-line';
            $data['url'] = route('admin.payments.crypto.show', $this->payment->id);
            $data['payment_id'] = (string) $this->payment->id;
            $data['payment_type'] = 'crypto';
            $data['amount'] = $this->payment->usd_amount;
            $data['crypto_type'] = $this->payment->crypto_type;
        } else {
            $data['icon'] = 'ri-bank-card-line';
            $data['url'] = route('admin.payments.show', $this->payment->id); // Add your regular payment route
            $data['payment_id'] = (string) $this->payment->id;
            $data['payment_type'] = 'regular';
            $data['amount'] = $this->payment->amount;
        }

        return $data;
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }
}