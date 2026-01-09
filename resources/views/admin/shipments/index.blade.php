@extends('layouts.admin')

@section('page-title', 'Shipment Management')

@section('content')
<div class="filters-card">
    <form action="{{ route('admin.shipments') }}" method="GET" class="row g-3">
        <div class="col-md-3">
            <input type="text" name="tracking_number" class="form-control" 
                   placeholder="Tracking Number" value="{{ request('tracking_number') }}">
        </div>
        <div class="col-md-3">
            <input type="email" name="user_email" class="form-control" 
                   placeholder="User Email" value="{{ request('user_email') }}">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-control">
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="date_from" class="form-control" 
                   value="{{ request('date_from') }}">
        </div>
        <div class="col-md-2">
            <input type="date" name="date_to" class="form-control" 
                   value="{{ request('date_to') }}">
        </div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.shipments') }}" class="btn btn-secondary">Clear</a>
        </div>
    </form>
</div>

<div class="admin-table">
    <div class="table-header">
        <h3 class="table-title">All Shipments ({{ $shipments->total() }})</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-hover datatable">
            <thead>
                <tr>
                    <th>Tracking #</th>
                    <th>Customer</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Status</th>
                    <th>Weight</th>
                    <th>Value</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shipments as $shipment)
                <tr>
                    <td>
                        <strong>{{ $shipment->tracking_number }}</strong>
                    </td>
                    <td>
                        {{ $shipment->user->email }}
                        @if($shipment->user->name)
                        <br><small>{{ $shipment->user->name }}</small>
                        @endif
                    </td>
                    <td>
                        @if($shipment->senderAddress)
                            {{ $shipment->senderAddress->city }}, {{ $shipment->senderAddress->country_code }}
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($shipment->recipientAddress)
                            {{ $shipment->recipientAddress->city }}, {{ $shipment->recipientAddress->country_code }}
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ str_replace('_', '-', $shipment->status) }}">
                            {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                        </span>
                    </td>
                    <td>{{ $shipment->weight }} kg</td>
                    <td>${{ number_format($shipment->declared_value, 2) }}</td>
                    <td>{{ $shipment->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('admin.shipments.show', $shipment->id) }}" 
                           class="btn btn-sm btn-primary" title="View Details">
                            <i class="ri-eye-line"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-3">
        {{ $shipments->appends(request()->query())->links() }}
    </div>
</div>
@endsection