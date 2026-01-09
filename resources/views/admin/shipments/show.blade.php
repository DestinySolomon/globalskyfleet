@extends('layouts.admin')

@section('page-title', 'Shipment Details - ' . $shipment->tracking_number)

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.shipments') }}" class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line me-2"></i>Back to Shipments
            </a>
        </div>
    </div>

    <!-- Shipment Header -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-1">Shipment: <strong>{{ $shipment->tracking_number }}</strong></h4>
                    <p class="text-muted mb-0">
                        Created: {{ $shipment->created_at->format('M d, Y H:i') }} | 
                        Estimated Delivery: {{ $shipment->estimated_delivery ? $shipment->estimated_delivery->format('M d, Y') : 'Not set' }}
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="badge bg-{{ $shipment->status === 'delivered' ? 'success' : ($shipment->status === 'pending' ? 'warning' : 'info') }} fs-6">
                        {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Shipment Details -->
        <div class="col-lg-8">
            <!-- Status Timeline -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="ri-history-line me-2"></i>Status Timeline</h6>
                </div>
                <div class="card-body">
                    @if($shipment->statusHistory->isEmpty())
                        <div class="text-center py-4">
                            <i class="ri-time-line fs-1 text-muted"></i>
                            <p class="mt-3">No tracking updates yet</p>
                        </div>
                    @else
                        <div class="timeline">
                            @foreach($shipment->statusHistory as $update)
                            <div class="timeline-item {{ $loop->first ? 'current' : '' }}">
                                <div class="timeline-marker">
                                    @if($loop->first)
                                        <i class="ri-checkbox-blank-circle-fill text-primary"></i>
                                    @else
                                        <i class="ri-checkbox-blank-circle-line text-muted"></i>
                                    @endif
                                </div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">{{ ucfirst(str_replace('_', ' ', $update->status)) }}</h6>
                                        <small class="text-muted">{{ $update->scan_datetime->format('M d, Y H:i') }}</small>
                                    </div>
                                    <p class="mb-1">{{ $update->location }}</p>
                                    @if($update->description)
                                        <p class="text-muted small mb-0">{{ $update->description }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white border-top">
                    <form action="{{ route('admin.shipments.update-status', $shipment->id) }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <select name="status" class="form-select" required>
                                    <option value="">Select Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="picked_up">Picked Up</option>
                                    <option value="in_transit">In Transit</option>
                                    <option value="customs_hold">Customs Hold</option>
                                    <option value="out_for_delivery">Out for Delivery</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="location" class="form-control" 
                                       placeholder="Location" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="description" class="form-control" 
                                       placeholder="Description (optional)">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Update Status</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Package Details -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="ri-box-3-line me-2"></i>Package Details</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="150">Weight:</th>
                                    <td>{{ $shipment->weight }} kg</td>
                                </tr>
                                <tr>
                                    <th>Dimensions:</th>
                                    <td>
                                        @if($shipment->dimensions)
                                            {{ json_decode($shipment->dimensions)->length ?? 'N/A' }} x 
                                            {{ json_decode($shipment->dimensions)->width ?? 'N/A' }} x 
                                            {{ json_decode($shipment->dimensions)->height ?? 'N/A' }} cm
                                        @else
                                            Not specified
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Declared Value:</th>
                                    <td>${{ number_format($shipment->declared_value, 2) }} {{ $shipment->currency }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="150">Insurance:</th>
                                    <td>
                                        @if($shipment->insurance_enabled)
                                            <span class="badge bg-success">Enabled</span> 
                                            ${{ number_format($shipment->insurance_amount, 2) }}
                                        @else
                                            <span class="badge bg-secondary">Disabled</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Signature Required:</th>
                                    <td>
                                        @if($shipment->requires_signature)
                                            <span class="badge bg-info">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dangerous Goods:</th>
                                    <td>
                                        @if($shipment->is_dangerous_goods)
                                            <span class="badge bg-danger">Yes</span>
                                        @else
                                            <span class="badge bg-success">No</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($shipment->content_description)
                    <div class="mt-3">
                        <h6>Content Description:</h6>
                        <p class="text-muted">{{ $shipment->content_description }}</p>
                    </div>
                    @endif
                    
                    @if($shipment->special_instructions)
                    <div class="mt-3">
                        <h6>Special Instructions:</h6>
                        <p class="text-muted">{{ $shipment->special_instructions }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Sender/Recipient & User Info -->
        <div class="col-lg-4">
            <!-- Sender Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="ri-user-location-line me-2"></i>Sender Information</h6>
                </div>
                <div class="card-body">
                    @if($shipment->senderAddress)
                        <p class="mb-1"><strong>{{ $shipment->senderAddress->contact_name }}</strong></p>
                        <p class="mb-1">{{ $shipment->senderAddress->company }}</p>
                        <p class="mb-1">{{ $shipment->senderAddress->address_line1 }}</p>
                        <p class="mb-1">{{ $shipment->senderAddress->address_line2 }}</p>
                        <p class="mb-1">{{ $shipment->senderAddress->city }}, {{ $shipment->senderAddress->state }} {{ $shipment->senderAddress->postal_code }}</p>
                        <p class="mb-0">{{ $shipment->senderAddress->country_code }}</p>
                        <p class="mt-2 mb-0">
                            <i class="ri-phone-line me-1"></i>
                            {{ $shipment->senderAddress->contact_phone }}
                        </p>
                    @else
                        <p class="text-muted">Not available</p>
                    @endif
                </div>
            </div>

            <!-- Recipient Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="ri-user-received-line me-2"></i>Recipient Information</h6>
                </div>
                <div class="card-body">
                    @if($shipment->recipientAddress)
                        <p class="mb-1"><strong>{{ $shipment->recipientAddress->contact_name }}</strong></p>
                        <p class="mb-1">{{ $shipment->recipientAddress->company }}</p>
                        <p class="mb-1">{{ $shipment->recipientAddress->address_line1 }}</p>
                        <p class="mb-1">{{ $shipment->recipientAddress->address_line2 }}</p>
                        <p class="mb-1">{{ $shipment->recipientAddress->city }}, {{ $shipment->recipientAddress->state }} {{ $shipment->recipientAddress->postal_code }}</p>
                        <p class="mb-0">{{ $shipment->recipientAddress->country_code }}</p>
                        <p class="mt-2 mb-0">
                            <i class="ri-phone-line me-1"></i>
                            {{ $shipment->recipientAddress->contact_phone }}
                        </p>
                    @else
                        <p class="text-muted">Not available</p>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="ri-user-line me-2"></i>Customer Information</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3" 
                             style="width: 48px; height: 48px; font-weight: bold;">
                            {{ substr($shipment->user->name, 0, 2) }}
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $shipment->user->name }}</h6>
                            <p class="text-muted mb-0">{{ $shipment->user->email }}</p>
                        </div>
                    </div>
                    
                    <table class="table table-sm">
                        <tr>
                            <th width="100">User ID:</th>
                            <td>#{{ $shipment->user->id }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $shipment->user->phone ?? 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <th>Company:</th>
                            <td>{{ $shipment->user->company ?? 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <th>Joined:</th>
                            <td>{{ $shipment->user->created_at->format('M d, Y') }}</td>
                        </tr>
                    </table>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.users.show', $shipment->user_id) }}" 
                           class="btn btn-sm btn-outline-primary w-100">
                            <i class="ri-user-line me-1"></i>View Customer Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e5e7eb;
}
.timeline-item {
    position: relative;
    margin-bottom: 25px;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    font-size: 1.2rem;
}
.timeline-item.current .timeline-marker {
    color: #0a2463;
}
</style>
@endsection