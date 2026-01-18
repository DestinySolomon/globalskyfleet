

<?php $__env->startSection('page-title', 'Document Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Document Preview</h5>
            </div>
            <div class="card-body">
                <?php if(str_starts_with($document->mime_type, 'image/')): ?>
                    <img src="<?php echo e(Storage::url($document->file_path)); ?>" 
                         alt="<?php echo e($document->original_name); ?>" 
                         class="img-fluid rounded">
                <?php elseif($document->mime_type === 'application/pdf'): ?>
                    <iframe src="<?php echo e(Storage::url($document->file_path)); ?>" 
                            class="w-100" height="600" 
                            style="border: 1px solid #dee2e6;"></iframe>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="ri-file-text-line display-1 text-muted"></i>
                        <p class="mt-3">Document cannot be previewed in browser</p>
                        <p class="text-muted">File Type: <?php echo e($document->mime_type); ?></p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="<?php echo e(Storage::url($document->file_path)); ?>" 
                           download="<?php echo e($document->original_name); ?>" 
                           class="btn btn-success">
                            <i class="ri-download-line"></i> Download
                        </a>
                        <a href="<?php echo e(route('admin.documents.view', $document->id)); ?>" 
                           target="_blank" class="btn btn-primary">
                            <i class="ri-external-link-line"></i> Open in New Tab
                        </a>
                    </div>
                    <div>
                        <a href="<?php echo e(route('admin.documents')); ?>" class="btn btn-secondary">
                            <i class="ri-arrow-left-line"></i> Back to Documents
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Document Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>Document Name:</th>
                        <td><?php echo e($document->original_name); ?></td>
                    </tr>
                    <tr>
                        <th>Type:</th>
                        <td>
                            <span class="badge bg-info"><?php echo e(ucfirst($document->type)); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th>File Size:</th>
                        <td><?php echo e(round($document->file_size / 1024, 2)); ?> KB</td>
                    </tr>
                    <tr>
                        <th>MIME Type:</th>
                        <td><?php echo e($document->mime_type); ?></td>
                    </tr>
                    <tr>
                        <th>Uploaded:</th>
                        <td><?php echo e($document->created_at->format('M d, Y H:i')); ?></td>
                    </tr>
                    <tr>
                        <th>Last Updated:</th>
                        <td><?php echo e($document->updated_at->format('M d, Y H:i')); ?></td>
                    </tr>
                </table>
                
                <?php if($document->description): ?>
                <div class="mt-3">
                    <h6>Description:</h6>
                    <p class="text-muted"><?php echo e($document->description); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">User Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>User:</th>
                        <td>
                            <a href="<?php echo e(route('admin.users.show', $document->user_id)); ?>">
                                <?php echo e($document->user->email); ?>

                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Name:</th>
                        <td><?php echo e($document->user->name ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Company:</th>
                        <td><?php echo e($document->user->company ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Phone:</th>
                        <td><?php echo e($document->user->phone ?? 'N/A'); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <?php if($document->shipment): ?>
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Shipment Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>Tracking #:</th>
                        <td>
                            <a href="<?php echo e(route('admin.shipments.show', $document->shipment_id)); ?>">
                                <?php echo e($document->shipment->tracking_number); ?>

                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <span class="badge badge-<?php echo e(str_replace('_', '-', $document->shipment->status)); ?>">
                                <?php echo e(ucfirst(str_replace('_', ' ', $document->shipment->status))); ?>

                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td><?php echo e($document->shipment->created_at->format('M d, Y')); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views\admin\documents\show.blade.php ENDPATH**/ ?>