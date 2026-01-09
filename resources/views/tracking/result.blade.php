@extends('layouts.app')

@section('title', 'Track Shipment - ' . $shipment->tracking_number)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Tracking Header -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1">Tracking: <strong>{{ $shipment->tracking_number }}</strong></h4>
                            <p class="text-muted mb-0">
                                From: {{ $shipment->senderAddress->city ?? 'Unknown' }} 
                                → To: {{ $shipment->recipientAddress->city ?? 'Unknown' }}
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <span class="badge bg-{{ $shipment->status === 'delivered' ? 'success' : 'primary' }} fs-6">
                                {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Shipment Timeline</h5>
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
            </div>

            <!-- Shipment Details -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Sender Information</h6>
                        </div>
                        <div class="card-body">
                            @if($shipment->senderAddress)
                                <p class="mb-1"><strong>{{ $shipment->senderAddress->contact_name }}</strong></p>
                                <p class="mb-1">{{ $shipment->senderAddress->address_line1 }}</p>
                                <p class="mb-1">{{ $shipment->senderAddress->city }}, {{ $shipment->senderAddress->state }}</p>
                                <p class="mb-0">{{ $shipment->senderAddress->country_code }}</p>
                            @else
                                <p class="text-muted">Not available</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Recipient Information</h6>
                        </div>
                        <div class="card-body">
                            @if($shipment->recipientAddress)
                                <p class="mb-1"><strong>{{ $shipment->recipientAddress->contact_name }}</strong></p>
                                <p class="mb-1">{{ $shipment->recipientAddress->address_line1 }}</p>
                                <p class="mb-1">{{ $shipment->recipientAddress->city }}, {{ $shipment->recipientAddress->state }}</p>
                                <p class="mb-0">{{ $shipment->recipientAddress->country_code }}</p>
                            @else
                                <p class="text-muted">Not available</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Track Another Button -->
            <div class="text-center mt-4">
                <a href="{{ route('tracking') }}" class="btn btn-primary">
                    <i class="ri-search-line me-2"></i>Track Another Shipment
                </a>
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