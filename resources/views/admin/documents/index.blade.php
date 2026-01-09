@extends('layouts.admin')

@section('page-title', 'Document Management')

@section('content')
<div class="container-fluid">
    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.documents') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Search by name or user..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="invoice" {{ request('type') == 'invoice' ? 'selected' : '' }}>Invoice</option>
                                <option value="id_proof" {{ request('type') == 'id_proof' ? 'selected' : '' }}>ID Proof</option>
                                <option value="commercial_invoice" {{ request('type') == 'commercial_invoice' ? 'selected' : '' }}>Commercial Invoice</option>
                                <option value="shipping_label" {{ request('type') == 'shipping_label' ? 'selected' : '' }}>Shipping Label</option>
                                <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents List -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ri-file-text-line me-2"></i>Uploaded Documents</h5>
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary">{{ $totalDocuments }} total</span>
                        @if($pendingDocuments > 0)
                        <span class="badge bg-warning">{{ $pendingDocuments }} pending</span>
                        @endif
                        <span class="badge bg-success">{{ $verifiedDocuments }} verified</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($documents->isEmpty())
                    <div class="text-center py-5">
                        <i class="ri-file-text-line display-1 text-muted opacity-25"></i>
                        <h5 class="mt-3 text-muted">No Documents Found</h5>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="200">Document</th>
                                    <th width="150">User</th>
                                    <th width="120">Type</th>
                                    <th width="100">Size</th>
                                    <th width="120">Status</th>
                                    <th width="150">Uploaded</th>
                                    <th width="200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $document)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded bg-light p-2 me-3">
                                                @if(str_starts_with($document->mime_type, 'image/'))
                                                    <i class="ri-image-line text-primary"></i>
                                                @elseif($document->mime_type === 'application/pdf')
                                                    <i class="ri-file-pdf-line text-danger"></i>
                                                @else
                                                    <i class="ri-file-text-line text-secondary"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <strong class="d-block">{{ $document->original_name }}</strong>
                                                <small class="text-muted">
                                                    @if($document->shipment)
                                                        Shipment: {{ $document->shipment->tracking_number }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" 
                                                 style="width: 32px; height: 32px; font-size: 12px; font-weight: bold;">
                                                {{ substr($document->user->name, 0, 2) }}
                                            </div>
                                            <div class="ms-2">
                                                <small class="d-block">{{ $document->user->name }}</small>
                                                <small class="text-muted">{{ $document->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($document->type) }}</span>
                                    </td>
                                    <td>
                                        <small>{{ round($document->file_size / 1024, 1) }} KB</small>
                                    </td>
                                    <td>
                                        @if($document->status === 'verified')
                                            <span class="badge bg-success">Verified</span>
                                        @elseif($document->status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $document->created_at->format('M d, Y') }}</small>
                                        <br>
                                        <small class="text-muted">{{ $document->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ Storage::url($document->file_path) }}" 
                                               target="_blank" class="btn btn-outline-primary" title="View">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ Storage::url($document->file_path) }}" 
                                               download="{{ $document->original_name }}" 
                                               class="btn btn-outline-success" title="Download">
                                                <i class="ri-download-line"></i>
                                            </a>
                                            
                                            @if($document->status !== 'verified')
                                            <form action="{{ route('admin.documents.verify', $document->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="verified">
                                                <button type="submit" class="btn btn-outline-success" 
                                                        title="Mark as Verified">
                                                    <i class="ri-check-line"></i>
                                                </button>
                                            </form>
                                            @endif
                                            
                                            @if($document->status !== 'rejected')
                                            <form action="{{ route('admin.documents.verify', $document->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-outline-danger" 
                                                        title="Reject Document"
                                                        onclick="return confirm('Are you sure you want to reject this document?');">
                                                    <i class="ri-close-line"></i>
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
                    
                    @if($documents->hasPages())
                    <div class="p-3 border-top">
                        {{ $documents->links() }}
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection