<?php

namespace App\Events;

use App\Models\Shipment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShipmentStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $shipment;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new event instance.
     */
    public function __construct(Shipment $shipment, string $oldStatus, string $newStatus)
    {
        $this->shipment = $shipment;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }
}