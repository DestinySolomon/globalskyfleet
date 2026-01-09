@extends('layouts.dashboard')

@section('title', 'Upload Document | GlobalSkyFleet')
@section('page-title', 'Upload Document')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex align-items-center">
                <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary btn-sm me-3">
                    <i class="ri-arrow-left-line"></i>
                </a>
                <div>
                    <h4 class="mb-0">
                        <i class="ri-upload-line me-2 text-primary"></i>
                        Upload New Document
                    </h4>
                    <p class="text-muted mb-0">Upload shipping documents, invoices, or proof of delivery</p>
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-semibold">
                    <i class="ri-file-upload-line me-2"></i>Document Details
                </h5>
            </div>
            
            <div class="card-body">
                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Shipment Selection -->
                    <div class="mb-4">
                        <label for="shipment_id" class="form-label">
                            Associated Shipment <span class="text-danger">*</span>
                        </label>
                        <select name="shipment_id" id="shipment_id" class="form-select" required>
                            <option value="">Select a shipment</option>
                            @foreach($shipments as $shipment)
                            <option value="{{ $shipment->id }}" {{ old('shipment_id') == $shipment->id ? 'selected' : '' }}>
                                {{ $shipment->tracking_number }} - Created {{ $shipment->created_at->format('M d, Y') }}
                            </option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            Select the shipment this document is related to
                        </div>
                    </div>

                    <!-- Document Type -->
                    <div class="mb-4">
                        <label for="document_type" class="form-label">
                            Document Type <span class="text-danger">*</span>
                        </label>
                        <select name="document_type" id="document_type" class="form-select" required>
                            <option value="">Select document type</option>
                            @foreach($documentTypes as $key => $label)
                            <option value="{{ $key }}" {{ old('document_type') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Document Name -->
                    <div class="mb-4">
                        <label for="document_name" class="form-label">
                            Document Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="document_name" 
                               id="document_name"
                               class="form-control"
                               value="{{ old('document_name') }}"
                               placeholder="e.g., Commercial Invoice for Shipment #123"
                               required>
                        <div class="form-text">
                            Give your document a descriptive name for easy identification
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-4">
                        <label for="document_file" class="form-label">
                            Document File <span class="text-danger">*</span>
                        </label>
                        <div class="file-upload-area">
                            <input type="file" 
                                   name="document_file" 
                                   id="document_file"
                                   class="form-control"
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx"
                                   required>
                            <div class="form-text">
                                Accepted formats: PDF, JPG, PNG, DOC, DOCX, XLS, XLSX (Max: 5MB)
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea name="description" 
                                  id="description" 
                                  class="form-control" 
                                  rows="3"
                                  placeholder="Add any additional notes or details about this document">{{ old('description') }}</textarea>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between pt-3 border-top">
                        <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-close-line me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-upload-line me-2"></i>Upload Document
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Help Information -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">
                    <i class="ri-information-line me-2"></i>Document Guidelines
                </h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="ri-checkbox-circle-line text-success me-2"></i>
                        <strong>Shipping Labels:</strong> Required for all shipments
                    </li>
                    <li class="mb-2">
                        <i class="ri-checkbox-circle-line text-success me-2"></i>
                        <strong>Commercial Invoices:</strong> Required for international shipments over $2500
                    </li>
                    <li class="mb-2">
                        <i class="ri-checkbox-circle-line text-success me-2"></i>
                        <strong>Customs Documents:</strong> Keep for 5 years for audit purposes
                    </li>
                    <li>
                        <i class="ri-checkbox-circle-line text-success me-2"></i>
                        <strong>Proof of Delivery:</strong> Required for claims and disputes
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // File upload preview
    const fileInput = document.getElementById('document_file');
    const fileNameDisplay = document.getElementById('file_name_display');
    
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                
                // Validate file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size exceeds 5MB limit. Please choose a smaller file.');
                    this.value = '';
                    return;
                }
                
                // Validate file type
                const allowedTypes = [
                    'application/pdf',
                    'image/jpeg',
                    'image/png',
                    'image/gif',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ];
                
                if (!allowedTypes.includes(file.type)) {
                    alert('File type not allowed. Please upload PDF, image, or document files.');
                    this.value = '';
                    return;
                }
            }
        });
    }
});
</script>
@endsection