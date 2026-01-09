@extends('layouts.dashboard')

@section('title', 'Upload Payment Proof - GlobalSkyFleet')

@section('page-title', 'Upload Payment Proof')

@section('content')
<div class="upload-proof-page">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Upload Payment Proof</h1>
            <p class="text-muted mb-0">Submit proof for your payment</p>
        </div>
        <div>
            <a href="{{ route('billing.payments') }}" class="btn btn-outline-primary">
                <i class="ri-arrow-left-line"></i> Back to Payments
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Payment Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Payment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><span class="text-muted">Invoice:</span> 
                                <strong>{{ $payment->invoice->invoice_number ?? 'N/A' }}</strong>
                            </p>
                            <p class="mb-2"><span class="text-muted">Payment Method:</span> 
                                <span class="badge bg-info">
                                    {{ str_replace('_', ' ', $payment->crypto_type) }}
                                </span>
                            </p>
                            <p class="mb-2"><span class="text-muted">Amount:</span> 
                                <strong>
                                    @if($payment->crypto_type === 'BTC')
                                        {{ number_format($payment->crypto_amount, 8) }} BTC
                                    @else
                                        {{ number_format($payment->usdt_amount, 2) }} USDT
                                    @endif
                                </strong>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><span class="text-muted">Transaction ID:</span> 
                                <code>{{ $payment->transaction_id }}</code>
                            </p>
                            <p class="mb-2"><span class="text-muted">Status:</span> 
                                <span class="badge {{ $payment->status === 'pending' ? 'bg-warning' : 'bg-info' }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </p>
                            <p class="mb-0"><span class="text-muted">Submitted:</span> 
                                {{ $payment->created_at->format('M d, Y - h:i A') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Upload Proof</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('billing.upload-proof', $payment) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        
                        @if($payment->payment_proof)
                            <!-- Current Proof -->
                            <div class="alert alert-info mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="ri-information-line fs-4 me-3"></i>
                                    <div>
                                        <h6 class="alert-heading mb-1">Proof Already Uploaded</h6>
                                        <p class="mb-0">You have already uploaded proof for this payment. Uploading a new file will replace the existing one.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4 text-center">
                                <img src="{{ Storage::url($payment->payment_proof) }}" 
                                     alt="Current Payment Proof" 
                                     class="img-fluid rounded shadow-sm mb-3"
                                     style="max-height: 300px;">
                                <div>
                                    <a href="{{ Storage::url($payment->payment_proof) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="ri-external-link-line"></i> View Full Image
                                    </a>
                                </div>
                            </div>
                        @endif

                        <!-- File Upload -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Select Proof Image</label>
                            <div class="upload-area" id="uploadArea">
                                <div class="upload-placeholder">
                                    <i class="ri-upload-cloud-2-line display-4 text-muted"></i>
                                    <h5 class="mt-3">Drop your file here</h5>
                                    <p class="text-muted mb-3">or click to browse</p>
                                    <div class="btn btn-primary">
                                        <i class="ri-folder-open-line"></i> Browse Files
                                    </div>
                                    <p class="small text-muted mt-3">
                                        Supported formats: JPG, PNG, GIF<br>
                                        Max file size: 2MB
                                    </p>
                                </div>
                                <input type="file" 
                                       class="form-control d-none" 
                                       id="payment_proof" 
                                       name="payment_proof" 
                                       accept="image/*" 
                                       required>
                            </div>
                            <div id="filePreview" class="d-none mt-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-file-image-line fs-2 text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1" id="fileName"></h6>
                                                <p class="text-muted small mb-1" id="fileSize"></p>
                                                <div class="progress mb-2" style="height: 4px;">
                                                    <div class="progress-bar" id="uploadProgress" style="width: 0%"></div>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <button type="button" class="btn btn-sm btn-outline-danger" id="removeFile">
                                                    <i class="ri-close-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Additional Notes (Optional)</label>
                            <textarea class="form-control" name="notes" rows="3" 
                                      placeholder="Add any additional information about this payment..."></textarea>
                        </div>

                        <!-- Requirements -->
                        <div class="alert alert-warning mb-4">
                            <h6><i class="ri-alert-line"></i> Proof Requirements:</h6>
                            <ul class="mb-0 small">
                                <li>Screenshot must clearly show the transaction details</li>
                                <li>Transaction ID should be visible</li>
                                <li>Date and time of transaction should be shown</li>
                                <li>Amount sent must match the invoice amount</li>
                                <li>Make sure the recipient address is correct</li>
                            </ul>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                <i class="ri-upload-cloud-2-line"></i> Upload Proof
                            </button>
                            <a href="{{ route('billing.payments') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.upload-proof-page .upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    padding: 3rem 1rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    background-color: #f8f9fa;
}

.upload-proof-page .upload-area:hover {
    border-color: #3b82f6;
    background-color: #eff6ff;
}

.upload-proof-page .upload-area.dragover {
    border-color: #10b981;
    background-color: #ecfdf5;
}

.upload-proof-page .upload-placeholder {
    color: #6b7280;
}

.upload-proof-page .progress {
    background-color: #e5e7eb;
}

.upload-proof-page .progress-bar {
    background-color: #3b82f6;
    transition: width 0.3s ease;
}

.upload-proof-page img {
    max-width: 100%;
    height: auto;
    border: 1px solid #dee2e6;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('payment_proof');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const uploadProgress = document.getElementById('uploadProgress');
    const removeFileBtn = document.getElementById('removeFile');
    const uploadForm = document.getElementById('uploadForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Click upload area to trigger file input
    uploadArea.addEventListener('click', function() {
        fileInput.click();
    });
    
    // Drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        uploadArea.classList.add('dragover');
    }
    
    function unhighlight() {
        uploadArea.classList.remove('dragover');
    }
    
    // Handle dropped files
    uploadArea.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            handleFiles(files[0]);
        }
    }
    
    // Handle file input change
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            handleFiles(this.files[0]);
        }
    });
    
    // Handle selected file
    function handleFiles(file) {
        // Check file type
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file (JPG, PNG, GIF)');
            return;
        }
        
        // Check file size (2MB limit)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            return;
        }
        
        // Update preview
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        uploadProgress.style.width = '0%';
        
        // Show preview
        filePreview.classList.remove('d-none');
        uploadArea.classList.add('d-none');
        
        // Simulate upload progress (for demo)
        let progress = 0;
        const interval = setInterval(() => {
            progress += 10;
            uploadProgress.style.width = `${progress}%`;
            
            if (progress >= 100) {
                clearInterval(interval);
            }
        }, 100);
    }
    
    // Remove file
    removeFileBtn.addEventListener('click', function() {
        fileInput.value = '';
        filePreview.classList.add('d-none');
        uploadArea.classList.remove('d-none');
        uploadProgress.style.width = '0%';
    });
    
    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Form submission
    uploadForm.addEventListener('submit', function(e) {
        if (!fileInput.files.length) {
            e.preventDefault();
            alert('Please select a proof image to upload');
            return;
        }
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ri-loader-4-line spinner"></i> Uploading...';
    });
});
</script>
@endsection