@extends('layouts.dashboard')

@section('title', 'Document Search | GlobalSkyFleet')
@section('page-title', 'Document Search')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0">
                    <i class="ri-search-line me-2 text-primary"></i>
                    Document Search Results
                    @if(Auth::user()->isAdmin())
                    <span class="badge bg-danger ms-2">Admin View</span>
                    @endif
                </h4>
                <p class="text-muted mb-0">
                    @if(Auth::user()->isAdmin())
                    Searching all documents from all users
                    @else
                    Search through your shipping documents
                    @endif
                </p>
            </div>
            <div>
                <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
                    <i class="ri-arrow-left-line me-2"></i>Back to Documents
                </a>
            </div>
        </div>

        <!-- Search Form -->
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
                                @foreach($documentTypes as $key => $label)
                                    <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
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

        <!-- Search Results -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="ri-file-list-line me-2"></i>
                        Search Results
                    </h5>
                    <div>
                        <span class="badge bg-primary">
                            {{ $documents->total() }} documents found
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                @if($documents->count() > 0)
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
                            @foreach($documents as $document)
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
                                            @if($document->description)
                                            <small class="d-block text-muted mt-1">
                                                <i class="ri-information-line me-1"></i>
                                                {{ Str::limit($document->description, 50) }}
                                            </small>
                                            @endif
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
                                    <div class="text-nowrap">
                                        {{ $document->created_at->format('M d, Y') }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $document->created_at->format('h:i A') }}
                                    </small>
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

                <!-- Pagination -->
                @if($documents->hasPages())
                <div class="card-footer bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing {{ $documents->firstItem() }} to {{ $documents->lastItem() }} of {{ $documents->total() }} results
                        </div>
                        <nav aria-label="Page navigation">
                            {{ $documents->withQueryString()->links() }}
                        </nav>
                    </div>
                </div>
                @endif
                
                @else
                <div class="text-center py-5">
                    <i class="ri-search-eye-line text-muted" style="font-size: 3rem;"></i>
                    <h5 class="text-muted mt-3">No documents found</h5>
                    <p class="text-muted">
                        @if(Auth::user()->isAdmin())
                        No documents match your search criteria across all users
                        @else
                        No documents match your search criteria
                        @endif
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary me-2">
                            <i class="ri-arrow-left-line me-2"></i>Back to Documents
                        </a>
                        <button onclick="clearSearch()" class="btn btn-primary">
                            <i class="ri-refresh-line me-2"></i>Clear Search
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Search Tips -->
        @if($documents->count() > 0)
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="ri-lightbulb-line text-warning" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Search Tips</h6>
                        <p class="text-muted mb-0">
                            <small>
                                @if(Auth::user()->isAdmin())
                                Try searching by user email, name, or tracking number for better results.
                                @else
                                Try searching by document name or tracking number for better results.
                                @endif
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Admin notification
    @if(Auth::user()->isAdmin())
    console.log('Admin mode active - searching all documents');
    @endif
});

function clearSearch() {
    // Clear all form inputs
    document.querySelector('input[name="search"]').value = '';
    document.querySelector('select[name="type"]').selectedIndex = 0;
    document.querySelector('input[name="date_from"]').value = '';
    document.querySelector('input[name="date_to"]').value = '';
    
    // Submit the form to show all documents
    document.querySelector('form').submit();
}

// Initialize date inputs with today's date as max
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const dateFrom = document.querySelector('input[name="date_from"]');
    const dateTo = document.querySelector('input[name="date_to"]');
    
    if (dateFrom) dateFrom.max = today;
    if (dateTo) dateTo.max = today;
    
    // Update max date for date_to when date_from changes
    if (dateFrom && dateTo) {
        dateFrom.addEventListener('change', function() {
            dateTo.min = this.value;
        });
    }
});
</script>
@endsection