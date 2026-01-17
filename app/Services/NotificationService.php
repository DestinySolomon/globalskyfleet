<?php

namespace App\Services;

use App\Models\User;
use App\Models\Shipment;
use App\Models\Payment;
use App\Models\CryptoPayment;
use App\Models\Document;
use App\Notifications\ShipmentNotification;
use App\Notifications\PaymentNotification;
use App\Notifications\DocumentNotification;
use App\Notifications\SystemNotification;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send shipment notification to user
     */
    public static function notifyUserAboutShipment(User $user, Shipment $shipment, $title, $message, $priority = 'normal')
    {
        try {
            $user->notify(new ShipmentNotification($shipment, $title, $message, $priority));
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send shipment notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send shipment notification to admins
     */
    public static function notifyAdminsAboutShipment(Shipment $shipment, $title, $message, $priority = 'normal')
    {
        try {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new ShipmentNotification($shipment, $title, $message, $priority));
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send admin shipment notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send payment notification
     */
    public static function notifyAboutPayment($payment, User $user, $title, $message, $priority = 'normal')
    {
        try {
            $user->notify(new PaymentNotification($payment, $title, $message, $priority));
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send payment notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send crypto payment notification to admins
     */
    public static function notifyAdminsAboutCryptoPayment(CryptoPayment $payment, $title, $message, $priority = 'normal')
    {
        try {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new PaymentNotification($payment, $title, $message, $priority));
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send admin crypto payment notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send document notification
     */
    public static function notifyAboutDocument(Document $document, $title, $message, $priority = 'normal')
    {
        try {
            // Notify the document owner
            $document->user->notify(new DocumentNotification($document, $title, $message, $priority));
            
            // Also notify admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new DocumentNotification($document, $title, $message, $priority));
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send document notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send system notification to all admins
     */
    public static function notifyAdmins($title, $message, $category = 'system', $priority = 'normal')
    {
        try {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new SystemNotification($title, $message, $category, $priority));
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send system notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send user notification to admins
     */
    public static function notifyAdminsAboutUser(User $user, $title, $message, $priority = 'normal')
    {
        try {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new UserNotification($user, $title, $message, $priority));
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send user notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification to specific user
     */
    public static function notifyUser(User $user, $title, $message, $category = 'system', $priority = 'normal')
    {
        try {
            $user->notify(new SystemNotification($title, $message, $category, $priority));
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send user notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Mark all notifications as read for a user
     */
    public static function markAllAsRead(User $user)
    {
        try {
            $user->unreadNotifications()->update(['read_at' => now()]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to mark notifications as read: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get unread count for a user
     */
    public static function getUnreadCount(User $user)
    {
        return $user->unreadNotifications()->count();
    }

    /**
     * Get recent notifications for a user
     */
    public static function getRecentNotifications(User $user, $limit = 10)
    {
        return $user->notifications()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Create notification for shipment status update
     */
    public static function shipmentStatusUpdated(Shipment $shipment, $oldStatus, $newStatus)
    {
        $title = "Shipment Status Updated";
        $message = "Shipment #{$shipment->tracking_number} status changed from " . 
                  ucfirst(str_replace('_', ' ', $oldStatus)) . " to " . 
                  ucfirst(str_replace('_', ' ', $newStatus));
        
        // Notify user
        self::notifyUserAboutShipment($shipment->user, $shipment, $title, $message);
        
        // Notify admins
        self::notifyAdminsAboutShipment($shipment, $title, $message);
    }

    /**
     * Create notification for new document upload
     */
    public static function documentUploaded(Document $document)
    {
        $title = "New Document Uploaded";
        $message = "User {$document->user->name} uploaded a new document: " . 
                  ucfirst(str_replace('_', ' ', $document->type));
        
        self::notifyAboutDocument($document, $title, $message, 'normal');
    }

    /**
     * Create notification for crypto payment received
     */
    public static function cryptoPaymentReceived(CryptoPayment $payment)
    {
        $title = "New Crypto Payment Received";
        $message = "Payment of $" . number_format($payment->usd_amount, 2) . 
                  " via {$payment->crypto_type} requires verification";
        
        self::notifyAdminsAboutCryptoPayment($payment, $title, $message, 'high');
    }
}