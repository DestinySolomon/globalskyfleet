@extends('layouts.dashboard')

@section('title', 'Documents | GlobalSkyFleet')
@section('page-title', 'Documents')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0">
                    <i class="ri-folder-line me-2 text-primary"></i>
                    Document Center
                    @if(Auth::user()->isAdmin())
                    <span class="badge bg-danger ms-2">Admin View</span>
                    @endif
                </h4>
                <p class="text-muted mb-0">
                    @if(Auth::user()->isAdmin())
                    Viewing all documents from all users
                    @else
                    Manage all your shipping documents in one place
                    @endif
                </p>
            </div>
            <div>
                <a href="{{ route('documents.create') }}" class="btn btn-primary">
                    <i class="ri-upload-line me-2"></i>Upload Document
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Documents</h6>
                                <h3 class="mb-0">{{ $stats['total'] }}</h3>
                                @if(Auth::user()->isAdmin())
                                <small class="text-muted">All Users</small>
                                @endif
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="ri-file-text-line text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Shipping Labels</h6>
                                <h3 class="mb-0">{{ $stats['shipping_labels'] }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="ri-price-tag-3-line text-success" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Invoices</h6>
                                <h3 class="mb-0">{{ $stats['invoices'] }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <i class="ri-bill-line text-warning" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Customs Docs</h6>
                                <h3 class="mb-0">{{ $stats['customs'] }}</h3>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded">
                                <i class="ri-passport-line text-info" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('documents.search') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Search by document name, tracking number{{ Auth::user()->isAdmin() ? ', or user' : '' }}"
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="type" class="form-select">
                                <option value="">All Document Types</option>
                                <option value="shipping_label" {{ request('type') == 'shipping_label' ? 'selected' : '' }}>Shipping Labels</option>
                                <option value="invoice" {{ request('type') == 'invoice' ? 'selected' : '' }}>Invoices</option>
                                <option value="customs_document" {{ request('type') == 'customs_document' ? 'selected' : '' }}>Customs Documents</option>
                                <option value="delivery_proof" {{ request('type') == 'delivery_proof' ? 'selected' : '' }}>Proof of Delivery</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" 
                                   name="date_from" 
                                   class="form-control" 
                                   placeholder="From Date"
                                   value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" 
                                   name="date_to" 
                                   class="form-control" 
                                   placeholder="To Date"
                                   value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ri-search-line"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Recent Documents -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-semibold">
                    <i class="ri-history-line me-2"></i>
                    @if(Auth::user()->isAdmin())
                    All Recent Documents
                    @else
                    Your Recent Documents
                    @endif
                </h5>
            </div>
            <div class="card-body p-0">
                @if($recentDocuments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Document</th>
                                @if(Auth::user()->isAdmin())
                                <th>Uploaded By</th>
                                @endif
                                <th>Type</th>
                                <th>Shipment</th>
                                <th>Uploaded</th>
                                <th>Size</th>
                                <th class="pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentDocuments as $document)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <div class="bg-light p-2 rounded">
                                                <i class="{{ $document->file_icon }} 
                                                    @if($document->is_pdf) text-danger
                                                    @elseif($document->is_image) text-success
                                                    @else text-primary @endif">
                                                </i>
                                            </div>
                                        </div>
                                        <div>
                                            <strong class="d-block">{{ $document->name }}</strong>
                                            <small class="text-muted">{{ $document->original_name }}</small>
                                        </div>
                                    </div>
                                </td>
                                
                                @if(Auth::user()->isAdmin())
                                <td>
                                    <div>
                                        <strong>{{ $document->user->name ?? 'Unknown' }}</strong><br>
                                        <small class="text-muted">{{ $document->user->email ?? '' }}</small>
                                    </div>
                                </td>
                                @endif
                                
                                <td>
                                    <span class="badge 
                                        @if($document->type === 'shipping_label') bg-primary
                                        @elseif($document->type === 'invoice') bg-success
                                        @elseif($document->type === 'customs_document') bg-warning
                                        @else bg-info @endif">
                                        {{ $document->type_label }}
                                    </span>
                                </td>
                                <td>
                                    @if($document->shipment)
                                    <a href="{{ route('shipments.show', $document->shipment) }}" class="text-decoration-none">
                                        {{ $document->shipment->tracking_number }}
                                    </a>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $document->created_at->format('M d, Y') }}
                                </td>
                                <td>
                                    <span class="text-muted">{{ $document->formatted_size }}</span>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('documents.view', $document) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="View" target="_blank">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('documents.show', $document) }}" 
                                           class="btn btn-sm btn-outline-success"
                                           title="Download">
                                            <i class="ri-download-line"></i>
                                        </a>
                                        @if(Auth::user()->isAdmin() || $document->user_id == Auth::id())
                                        <form action="{{ route('documents.destroy', $document) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this document?')">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="ri-folder-open-line text-muted" style="font-size: 3rem;"></i>
                    <h5 class="text-muted mt-3">No documents yet</h5>
                    <p class="text-muted">
                        @if(Auth::user()->isAdmin())
                        No documents have been uploaded by any users yet
                        @else
                        Upload your first document to get started
                        @endif
                    </p>
                    <a href="{{ route('documents.create') }}" class="btn btn-primary">
                        <i class="ri-upload-line me-2"></i>Upload Document
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Documents by Shipment -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-semibold">
                    <i class="ri-ship-line me-2"></i>
                    @if(Auth::user()->isAdmin())
                    Documents by Shipment (All Users)
                    @else
                    Your Documents by Shipment
                    @endif
                </h5>
            </div>
            <div class="card-body p-0">
                @if($shipments->count() > 0)
                <div class="accordion" id="shipmentDocuments">
                    @foreach($shipments as $shipment)
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#shipment{{ $shipment->id }}">
                                <div class="d-flex align-items-center w-100">
                                    <div class="me-3">
                                        <span class="badge bg-light text-dark">
                                            {{ $shipment->tracking_number }}
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-semibold">{{ $shipment->documents->count() }} documents</span>
                                            @if(Auth::user()->isAdmin())
                                            <small class="text-muted">
                                                Customer: {{ $shipment->user->name ?? 'Unknown' }}
                                            </small>
                                            @endif
                                        </div>
                                        <small class="text-muted d-block">
                                            Created: {{ $shipment->created_at->format('M d, Y') }}
                                        </small>
                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="shipment{{ $shipment->id }}" class="accordion-collapse collapse" data-bs-parent="#shipmentDocuments">
                            <div class="accordion-body">
                                <div class="row">
                                    @foreach($shipment->documents as $document)
                                    <div class="col-md-4 mb-3">
                                        <div class="card border h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="me-3">
                                                        <div class="bg-light p-2 rounded">
                                                            <i class="{{ $document->file_icon }} 
                                                                @if($document->is_pdf) text-danger
                                                                @elseif($document->is_image) text-success
                                                                @else text-primary @endif">
                                                            </i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">{{ $document->name }}</h6>
                                                        <small class="text-muted">{{ $document->type_label }}</small>
                                                        @if(Auth::user()->isAdmin())
                                                        <div class="mt-1">
                                                            <small class="text-muted">
                                                                Uploaded by: {{ $document->user->name ?? 'Unknown' }}
                                                            </small>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">{{ $document->formatted_size }}</small>
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ route('documents.view', $document) }}" 
                                                           class="btn btn-sm btn-outline-primary"
                                                           title="View" target="_blank">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                        <a href="{{ route('documents.show', $document) }}" 
                                                           class="btn btn-sm btn-outline-success"
                                                           title="Download">
                                                            <i class="ri-download-line"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <p class="text-muted mb-0">
                        @if(Auth::user()->isAdmin())
                        No shipments with documents yet
                        @else
                        No shipments with documents yet
                        @endif
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-expand accordion if URL has hash
    const hash = window.location.hash;
    if (hash) {
        const element = document.querySelector(hash);
        if (element && element.classList.contains('accordion-collapse')) {
            const bsCollapse = new bootstrap.Collapse(element, {
                toggle: false
            });
            bsCollapse.show();
        }
    }

    // Admin notification
    @if(Auth::user()->isAdmin())
    console.log('Admin mode active - viewing all documents');
    @endif
});
</script>
@endsection