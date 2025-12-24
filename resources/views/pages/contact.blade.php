@extends('layouts.app')

@section('title', 'Contact GlobalSkyFleet | Get in Touch with Our Logistics Team')

@section('description', 'Contact GlobalSkyFleet for all your logistics needs. Reach out via phone, email, or WhatsApp for support, quotes, and partnership opportunities.')

@section('keywords', 'contact GlobalSkyFleet, logistics support, shipping inquiry, customer service, get a quote, track shipment help')

@section('content')
    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="contact-icon">
                        <i class="ri-customer-service-2-line"></i>
                    </div>
                    <h1 class="display-4 fw-bold text-white mb-4">Get in Touch</h1>
                    <p class="text-white opacity-80 fs-5 mb-0">
                        Our team is here to help you with all your logistics needs
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-5">
        <div class="container">
            <div class="row g-5">
                <!-- Contact Form -->
                <div class="col-lg-6">
                    <div class="contact-form-card">
                        <h2 class="display-6 fw-bold text-navy mb-5">Send Us a Message</h2>
                        <form id="contactForm" method="POST" action="{{ route('contact.submit') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold text-navy mb-2">Full Name</label>
                                <input type="text" 
                                       class="form-control form-control-contact" 
                                       id="name" 
                                       name="name"
                                       value="{{ old('name') }}"
                                       placeholder="John Doe" 
                                       required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold text-navy mb-2">Email Address</label>
                                <input type="email" 
                                       class="form-control form-control-contact" 
                                       id="email" 
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="john@example.com" 
                                       required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="phone" class="form-label fw-semibold text-navy mb-2">Phone Number</label>
                                <input type="tel" 
                                       class="form-control form-control-contact" 
                                       id="phone" 
                                       name="phone"
                                       value="{{ old('phone') }}"
                                       placeholder="+1 (555) 123-4567" 
                                       required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="subject" class="form-label fw-semibold text-navy mb-2">Subject</label>
                                <select class="form-select form-select-contact" id="subject" name="subject" required>
                                    <option value="">Select a subject</option>
                                    <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                                    <option value="quote" {{ old('subject') == 'quote' ? 'selected' : '' }}>Request a Quote</option>
                                    <option value="tracking" {{ old('subject') == 'tracking' ? 'selected' : '' }}>Tracking Issue</option>
                                    <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>Partnership Opportunity</option>
                                    <option value="complaint" {{ old('subject') == 'complaint' ? 'selected' : '' }}>Complaint</option>
                                    <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="message" class="form-label fw-semibold text-navy mb-2">Message</label>
                                <textarea class="form-control form-control-contact" 
                                          id="message" 
                                          name="message"
                                          rows="5" 
                                          placeholder="Tell us how we can help you..." 
                                          required>{{ old('message') }}</textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-orange w-100 py-3 rounded-2 fw-semibold fs-5">
                                Send Message <i class="ri-send-plane-fill ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Contact Information & Quick Links -->
                <div class="col-lg-6">
                    <div class="row g-4">
                        <!-- Contact Information -->
                        <div class="col-12">
                            <div class="contact-info-card">
                                <h3 class="h2 fw-bold text-white mb-5">Contact Information</h3>
                                
                                <div class="contact-info-item">
                                    <div class="contact-info-icon">
                                        <i class="ri-mail-line"></i>
                                    </div>
                                    <div>
                                        <h4 class="h5 fw-semibold text-white mb-1">Email</h4>
                                        <a href="mailto:support@globalskyfleet.com" class="text-white opacity-80 hover-opacity-100 text-decoration-none">
                                            support@globalskyfleet.com
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="contact-info-item">
                                    <div class="contact-info-icon">
                                        <i class="ri-phone-line"></i>
                                    </div>
                                    <div>
                                        <h4 class="h5 fw-semibold text-white mb-1">Phone</h4>
                                        <a href="tel:+1-800-SKYFLEET" class="text-white opacity-80 hover-opacity-100 text-decoration-none d-block">
                                            +1-800-SKYFLEET
                                        </a>
                                        <a href="tel:+1-800-759-3533" class="text-white opacity-80 hover-opacity-100 text-decoration-none">
                                            +1-800-759-3533
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="contact-info-item">
                                    <div class="contact-info-icon">
                                        <i class="ri-map-pin-line"></i>
                                    </div>
                                    <div>
                                        <h4 class="h5 fw-semibold text-white mb-2">Office Locations</h4>
                                        <div class="text-white opacity-80">
                                            <p class="mb-1 small">New York: 350 Fifth Avenue, NY 10118</p>
                                            <p class="mb-1 small">London: 1 Canada Square, E14 5AB</p>
                                            <p class="mb-1 small">Dubai: Sheikh Zayed Road, Dubai</p>
                                            <p class="mb-0 small">Singapore: 1 Marina Boulevard, 018989</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="contact-info-item mb-0">
                                    <div class="contact-info-icon">
                                        <i class="ri-time-line"></i>
                                    </div>
                                    <div>
                                        <h4 class="h5 fw-semibold text-white mb-1">Business Hours</h4>
                                        <p class="text-white opacity-80 small mb-1">24/7 Customer Support</p>
                                        <p class="text-white opacity-80 small mb-0">Available Every Day</p>
                                    </div>
                                </div>
                                
                                <a href="https://wa.me/18007593533" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp w-100 mt-4 py-3 rounded-2 fw-semibold">
                                    <i class="ri-whatsapp-line me-2 fs-5"></i>Chat on WhatsApp
                                </a>
                            </div>
                        </div>
                        
                        <!-- Quick Links -->
                        <div class="col-12">
                            <div class="quick-links-card">
                                <h3 class="h2 fw-bold text-navy mb-4">Quick Links</h3>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <a href="{{ route('tracking') }}" class="quick-link-item">
                                            <i class="ri-map-pin-line"></i>
                                            <span>Track Shipment</span>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('quote') }}" class="quick-link-item">
                                            <i class="ri-file-list-3-line"></i>
                                            <span>Get a Quote</span>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('services') }}" class="quick-link-item">
                                            <i class="ri-service-line"></i>
                                            <span>Our Services</span>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('about') }}" class="quick-link-item">
                                            <i class="ri-information-line"></i>
                                            <span>About Us</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Modal -->
    @if(session('success'))
        <div class="modal fade show" id="successModal" tabindex="-1" style="display: block; padding-right: 17px;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-body text-center p-5">
                        <div class="contact-icon mb-4" style="width: 60px; height: 60px; background-color: rgba(16, 185, 129, 0.2);">
                            <i class="ri-check-line text-success fs-3"></i>
                        </div>
                        <h3 class="h3 fw-bold text-navy mb-3">Message Sent Successfully!</h3>
                        <p class="text-muted mb-4">
                            Thank you for contacting GlobalSkyFleet. Our team will get back to you within 24 hours.
                        </p>
                        <button type="button" class="btn btn-orange rounded-pill px-4" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.getElementById('contactForm');
        
        // If success modal exists, show it
        const successModal = document.getElementById('successModal');
        if (successModal) {
            const modal = new bootstrap.Modal(successModal);
            modal.show();
            
            // Close modal when clicking backdrop
            successModal.addEventListener('hidden.bs.modal', function () {
                document.querySelector('.modal-backdrop').remove();
            });
        }
        
        // Form validation and submission
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                const submitBtn = contactForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                // Show loading state
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Sending...';
                submitBtn.disabled = true;
                
                // The form will submit normally through Laravel
                // In a real application, you might want AJAX submission
            });
        }
        
        // Add hover effects to quick links
        const quickLinks = document.querySelectorAll('.quick-link-item');
        quickLinks.forEach(link => {
            link.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(5px)';
            });
            link.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
    });
</script>
@endpush