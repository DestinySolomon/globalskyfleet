@extends('layouts.admin')

@section('page-title', 'Email Templates')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line me-2"></i>Back to Settings
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="ri-list-check me-2"></i>Available Templates</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($templates as $key => $template)
                        <a href="#template-{{ $key }}" 
                           class="list-group-item list-group-item-action border-0 py-3"
                           data-bs-toggle="collapse" 
                           role="button"
                           aria-expanded="false">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $template['name'] }}</h6>
                                    <small class="text-muted">{{ $template['description'] }}</small>
                                </div>
                                <i class="ri-arrow-down-s-line"></i>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="accordion" id="templatesAccordion">
                @foreach($templates as $key => $template)
                <div class="accordion-item border-0 shadow-sm mb-3" id="template-{{ $key }}">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed bg-white" type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#collapse-{{ $key }}">
                            {{ $template['name'] }}
                        </button>
                    </h2>
                    <div id="collapse-{{ $key }}" class="accordion-collapse collapse" 
                         data-bs-parent="#templatesAccordion">
                        <div class="accordion-body">
                            <form action="{{ route('admin.settings.email.update', $key) }}" method="POST">
                                @csrf
                                
                                <div class="mb-3">
                                    <label class="form-label">Subject</label>
                                    <input type="text" name="subject" class="form-control" 
                                           value="{{ setting('email_template_' . $key . '_subject', $template['subject']) }}" 
                                           required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Email Content</label>
                                    <textarea name="content" class="form-control" rows="10" required>{{ setting('email_template_' . $key . '_content', '') }}</textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Available Variables</label>
                                    <div class="bg-light p-3 rounded">
                                        @foreach($template['variables'] as $variable)
                                        <code class="me-2 mb-1 d-inline-block">{{ $variable }}</code>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line me-2"></i>Save Template
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" 
                                            onclick="previewTemplate('{{ $key }}')">
                                        <i class="ri-eye-line me-2"></i>Preview
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview template in new window
    function previewTemplate(templateKey) {
        const form = document.querySelector(`#template-${templateKey} form`);
        const content = form.querySelector('textarea[name="content"]').value;
        const subject = form.querySelector('input[name="subject"]').value;
        
        const previewWindow = window.open('', '_blank');
        previewWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Preview: ${subject}</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; max-width: 600px; margin: 0 auto; padding: 20px; }
                    .email-header { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
                    .email-body { padding: 20px; border: 1px solid #dee2e6; border-radius: 8px; }
                    .variables { background: #fff3cd; padding: 10px; border-radius: 4px; margin-top: 20px; }
                    code { background: #e9ecef; padding: 2px 6px; border-radius: 4px; font-family: monospace; }
                </style>
            </head>
            <body>
                <div class="email-header">
                    <h3>Email Preview: ${subject}</h3>
                    <p><strong>Template:</strong> ${templateKey.replace('_', ' ').toUpperCase()}</p>
                </div>
                <div class="email-body">
                    ${content.replace(/\n/g, '<br>')}
                </div>
                <div class="variables">
                    <strong>Note:</strong> Variables like {{ '{' }}{{ '{' }}variable{{ '}' }}{{ '}' }} will be replaced with actual data when sent.
                </div>
            </body>
            </html>
        `);
        previewWindow.document.close();
    }
    
    // Auto-expand template when URL has hash
    document.addEventListener('DOMContentLoaded', function() {
        const hash = window.location.hash;
        if (hash) {
            const element = document.querySelector(hash);
            if (element) {
                const collapse = element.querySelector('.accordion-collapse');
                if (collapse) {
                    new bootstrap.Collapse(collapse, { toggle: true });
                }
            }
        }
    });
</script>
@endpush

<?php
// Helper function to get default template content
if (!function_exists('getDefaultTemplate')) {
    function getDefaultTemplate($templateKey) {
        $templates = [
            'welcome' => "Dear {{user_name}},\n\nWelcome to GlobalSkyFleet! We're excited to have you on board.\n\nYour account has been created successfully. You can now:\n- Create shipments\n- Track your packages\n- View invoices and payments\n- Manage your profile\n\nTo get started, please login at: {{login_url}}\n\nIf you have any questions, feel free to contact our support team.\n\nBest regards,\nThe GlobalSkyFleet Team",
            
            'invoice_created' => "Dear {{user_name}},\n\nA new invoice has been created for your account.\n\nInvoice Details:\n- Invoice Number: {{invoice_number}}\n- Amount Due: {{amount}}\n- Due Date: {{due_date}}\n\nYou can view and pay your invoice at: {{invoice_url}}\n\nIf you have any questions about this invoice, please contact our billing department.\n\nBest regards,\nGlobalSkyFleet Billing Team",
            
            'invoice_paid' => "Dear {{user_name}},\n\nThank you for your payment!\n\nPayment Confirmation:\n- Invoice Number: {{invoice_number}}\n- Amount Paid: {{amount}}\n- Payment Method: {{payment_method}}\n- Payment Date: {{payment_date}}\n\nYour payment has been processed successfully. You can download your receipt from your account dashboard.\n\nThank you for choosing GlobalSkyFleet!\n\nBest regards,\nGlobalSkyFleet Finance Team",
            
            'shipment_created' => "Dear {{user_name}},\n\nYour shipment has been created successfully!\n\nShipment Details:\n- Tracking Number: {{tracking_number}}\n- Current Status: {{shipment_status}}\n- Estimated Delivery: {{estimated_delivery}}\n\nYou can track your shipment here: {{tracking_url}}\n\nWe'll notify you as your shipment progresses through our network.\n\nThank you for choosing GlobalSkyFleet!\n\nBest regards,\nGlobalSkyFleet Shipping Team",
            
            'shipment_status' => "Dear {{user_name}},\n\nYour shipment status has been updated.\n\nTracking Number: {{tracking_number}}\nPrevious Status: {{old_status}}\nNew Status: {{new_status}}\n\nTrack your shipment: {{tracking_url}}\n\nIf you have any questions, please contact our support team.\n\nBest regards,\nGlobalSkyFleet Shipping Team",
            
            'shipment_delivered' => "Dear {{user_name}},\n\nGreat news! Your shipment has been delivered successfully.\n\nDelivery Details:\n- Tracking Number: {{tracking_number}}\n- Delivered On: {{delivery_date}}\n- Delivery Location: {{delivery_location}}\n\nWe hope everything arrived in perfect condition. If you have any concerns about your delivery, please contact us within 48 hours.\n\nThank you for choosing GlobalSkyFleet!\n\nBest regards,\nGlobalSkyFleet Delivery Team",
            
            'password_reset' => "Dear {{user_name}},\n\nYou requested a password reset for your GlobalSkyFleet account.\n\nClick the link below to reset your password:\n{{reset_url}}\n\nThis link will expire in {{expiry_time}}.\n\nIf you didn't request this password reset, please ignore this email or contact our support team.\n\nBest regards,\nGlobalSkyFleet Security Team",
        ];
        
        return $templates[$templateKey] ?? '';
    }
}
?>