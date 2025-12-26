@extends('layouts.dashboard')

@section('title', 'Shipment Details | GlobalSkyFleet')
@section('page-title', 'Shipment Details')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h4 class="mb-1">Shipment #{{ $shipment->tracking_number }}</h4>
                        <p class="text-muted mb-0">
                            Created: {{ $shipment->created_at->format('F d, Y') }}
                        </p>
                    </div>
                    <span class="badge 
                        @if($shipment->status === 'delivered') bg-success
                        @elseif($shipment->status === 'cancelled') bg-danger
                        @elseif(in_array($shipment->status, ['in_transit', 'out_for_delivery'])) bg-info
                        @else bg-warning @endif" 
                        style="font-size: 1rem;">
                        {{ ucfirst($shipment->status) }}
                    </span>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">From</h6>
                        <div class="bg-light p-3 rounded">
                            <strong>{{ $shipment->senderAddress->name ?? 'N/A' }}</strong><br>
                            {{ $shipment->senderAddress->address_line1 ?? '' }}<br>
                            {{ $shipment->senderAddress->city ?? '' }}, 
                            {{ $shipment->senderAddress->country ?? '' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">To</h6>
                        <div class="bg-light p-3 rounded">
                            <strong>{{ $shipment->recipientAddress->name ?? 'N/A' }}</strong><br>
                            {{ $shipment->recipientAddress->address_line1 ?? '' }}<br>
                            {{ $shipment->recipientAddress->city ?? '' }}, 
                            {{ $shipment->recipientAddress->country ?? '' }}
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <h6 class="text-muted mb-2">Weight</h6>
                        <p class="mb-0"><strong>{{ $shipment->weight }} kg</strong></p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted mb-2">Value</h6>
                        <p class="mb-0">
                            <strong>
                                {{ $shipment->currency }} {{ number_format($shipment->declared_value, 2) }}
                            </strong>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted mb-2">Estimated Delivery</h6>
                        <p class="mb-0">
                            <strong>
                                @if($shipment->estimated_delivery)
                                    {{ $shipment->estimated_delivery->format('M d, Y') }}
                                @else
                                    N/A
                                @endif
                            </strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Actions</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('tracking') }}?tracking_number={{ $shipment->tracking_number }}" 
                       class="btn btn-outline-primary" target="_blank">
                        <i class="ri-search-line me-2"></i>Track Shipment
                    </a>
                    
                    <a href="{{ route('shipments.index') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection