@extends('layouts.app')

@section('title', 'Privacy Policy | GlobalSkyFleet')

@section('description', 'GlobalSkyFleet Privacy Policy - Learn how we collect, use, and protect your personal information when you use our international shipping services.')

@section('keywords', 'privacy policy, data protection, personal information, GDPR, privacy statement')

@section('content')
    <!-- Hero Section -->
    <section class="legal-hero">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="legal-icon">
                        <i class="ri-shield-keyhole-line"></i>
                    </div>
                    <h1 class="display-4 fw-bold text-white mb-4">Privacy Policy</h1>
                    <p class="text-white opacity-80 fs-5 mb-0">
                        Last updated: {{ date('F d, Y') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Privacy Content -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="legal-content">
                        <!-- Introduction -->
                        <div class="legal-section">
                            <h2>Introduction</h2>
                            <p>GlobalSkyFleet Logistics Inc. ("GlobalSkyFleet," "we," "us," or "our") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our website, mobile application, and services.</p>
                            <p>Please read this Privacy Policy carefully. By accessing or using our services, you acknowledge that you have read, understood, and agree to be bound by all the terms of this Privacy Policy.</p>
                        </div>

                        <!-- Table of Contents -->
                        <div class="toc-container">
                            <h3 class="h4 fw-bold text-navy mb-3">Table of Contents</h3>
                            <ul class="toc-list">
                                <li><a href="#information">1. Information We Collect</a></li>
                                <li><a href="#use">2. How We Use Your Information</a></li>
                                <li><a href="#sharing">3. Information Sharing & Disclosure</a></li>
                                <li><a href="#protection">4. Data Protection & Security</a></li>
                                <li><a href="#retention">5. Data Retention</a></li>
                                <li><a href="#rights">6. Your Privacy Rights</a></li>
                                <li><a href="#cookies">7. Cookies & Tracking Technologies</a></li>
                                <li><a href="#third-party">8. Third-Party Links</a></li>
                                <li><a href="#children">9. Children's Privacy</a></li>
                                <li><a href="#international">10. International Data Transfers</a></li>
                                <li><a href="#changes">11. Changes to Privacy Policy</a></li>
                                <li><a href="#contact">12. Contact Us</a></li>
                            </ul>
                        </div>

                        <div class="legal-section" id="information">
                            <h2>1. Information We Collect</h2>
                            <h3>1.1 Personal Information</h3>
                            <p>We collect personal information that you voluntarily provide when you:</p>
                            <ul>
                                <li>Create an account</li>
                                <li>Request shipping quotes</li>
                                <li>Book shipments</li>
                                <li>Contact customer support</li>
                                <li>Subscribe to our newsletter</li>
                                <li>Participate in surveys or promotions</li>
                            </ul>
                            <p>This may include: name, email address, phone number, mailing address, company information, and payment details.</p>
                            
                            <h3>1.2 Shipping Information</h3>
                            <p>When you ship with us, we collect information about your shipments including:</p>
                            <ul>
                                <li>Package contents and value</li>
                                <li>Sender and recipient information</li>
                                <li>Shipping preferences and requirements</li>
                                <li>Customs documentation</li>
                            </ul>
                            
                            <h3>1.3 Automatically Collected Information</h3>
                            <p>We automatically collect certain information when you visit our website or use our mobile app:</p>
                            <ul>
                                <li>IP address and device information</li>
                                <li>Browser type and version</li>
                                <li>Pages visited and time spent</li>
                                <li>Referring website addresses</li>
                                <li>Operating system information</li>
                            </ul>
                        </div>

                        <div class="legal-section" id="use">
                            <h2>2. How We Use Your Information</h2>
                            <p>We use the information we collect for various purposes:</p>
                            <ul>
                                <li><strong>Service Delivery:</strong> To process and manage your shipments</li>
                                <li><strong>Communication:</strong> To send shipment updates and notifications</li>
                                <li><strong>Customer Support:</strong> To respond to your inquiries and provide assistance</li>
                                <li><strong>Billing:</strong> To process payments and send invoices</li>
                                <li><strong>Improvement:</strong> To enhance and personalize our services</li>
                                <li><strong>Marketing:</strong> To send promotional materials (with your consent)</li>
                                <li><strong>Compliance:</strong> To comply with legal obligations and prevent fraud</li>
                                <li><strong>Analytics:</strong> To analyze usage patterns and improve our website</li>
                            </ul>
                        </div>

                        <div class="legal-section" id="sharing">
                            <h2>3. Information Sharing & Disclosure</h2>
                            <p>We may share your information with:</p>
                            <h3>3.1 Service Providers</h3>
                            <p>Third-party companies that help us operate our business, such as:</p>
                            <ul>
                                <li>Carrier partners for shipment delivery</li>
                                <li>Payment processors for transaction handling</li>
                                <li>Cloud hosting providers for data storage</li>
                                <li>Customer support platforms</li>
                            </ul>
                            
                            <h3>3.2 Legal Requirements</h3>
                            <p>We may disclose your information if required by law, such as:</p>
                            <ul>
                                <li>To comply with legal obligations</li>
                                <li>To protect our rights and property</li>
                                <li>To prevent fraud or security issues</li>
                                <li>To protect the safety of our users or the public</li>
                            </ul>
                            
                            <h3>3.3 Business Transfers</h3>
                            <p>In the event of a merger, acquisition, or sale of assets, your information may be transferred as part of the transaction.</p>
                        </div>

                        <div class="legal-section" id="protection">
                            <h2>4. Data Protection & Security</h2>
                            <p>We implement appropriate technical and organizational measures to protect your personal information:</p>
                            <ul>
                                <li>SSL/TLS encryption for data transmission</li>
                                <li>Secure servers with firewalls</li>
                                <li>Regular security assessments and audits</li>
                                <li>Access controls and authentication systems</li>
                                <li>Employee training on data protection</li>
                            </ul>
                            <p>While we strive to protect your information, no method of transmission over the Internet or electronic storage is 100% secure.</p>
                        </div>

                        <div class="legal-section" id="retention">
                            <h2>5. Data Retention</h2>
                            <p>We retain your personal information only for as long as necessary:</p>
                            <ul>
                                <li><strong>Account Information:</strong> For the duration of your account plus 7 years</li>
                                <li><strong>Shipping Records:</strong> 7 years for tax and compliance purposes</li>
                                <li><strong>Customer Support:</strong> 3 years from last interaction</li>
                                <li><strong>Marketing Data:</strong> Until you opt-out or 3 years of inactivity</li>
                            </ul>
                            <p>We regularly review our data retention practices and delete information that is no longer needed.</p>
                        </div>

                        <div class="legal-section" id="rights">
                            <h2>6. Your Privacy Rights</h2>
                            <p>Depending on your location, you may have certain rights regarding your personal information:</p>
                            <ul>
                                <li><strong>Access:</strong> Request a copy of your personal information</li>
                                <li><strong>Correction:</strong> Request correction of inaccurate information</li>
                                <li><strong>Deletion:</strong> Request deletion of your personal information</li>
                                <li><strong>Restriction:</strong> Request restriction of processing</li>
                                <li><strong>Portability:</strong> Request transfer of your data to another service</li>
                                <li><strong>Objection:</strong> Object to processing of your data</li>
                                <li><strong>Withdraw Consent:</strong> Withdraw consent for marketing communications</li>
                            </ul>
                            <p>To exercise these rights, please contact us using the information in the "Contact Us" section.</p>
                        </div>

                        <div class="legal-section" id="cookies">
                            <h2>7. Cookies & Tracking Technologies</h2>
                            <p>We use cookies and similar tracking technologies to:</p>
                            <ul>
                                <li>Remember your preferences and settings</li>
                                <li>Analyze website traffic and usage</li>
                                <li>Enable essential website functions</li>
                                <li>Personalize your experience</li>
                                <li>Deliver targeted advertisements</li>
                            </ul>
                            <p>You can control cookies through your browser settings. However, disabling cookies may affect your ability to use certain features of our website.</p>
                        </div>

                        <div class="legal-section" id="third-party">
                            <h2>8. Third-Party Links</h2>
                            <p>Our website may contain links to third-party websites. This Privacy Policy does not apply to those websites. We encourage you to review the privacy policies of any third-party sites you visit.</p>
                        </div>

                        <div class="legal-section" id="children">
                            <h2>9. Children's Privacy</h2>
                            <p>Our services are not intended for children under 16 years of age. We do not knowingly collect personal information from children under 16. If you believe we have collected information from a child under 16, please contact us immediately.</p>
                        </div>

                        <div class="legal-section" id="international">
                            <h2>10. International Data Transfers</h2>
                            <p>As a global logistics company, we may transfer your personal information to countries outside your country of residence. When we do so, we implement appropriate safeguards to ensure your information remains protected.</p>
                            <p>For transfers from the European Economic Area (EEA), we rely on adequacy decisions or appropriate safeguards such as Standard Contractual Clauses.</p>
                        </div>

                        <div class="legal-section" id="changes">
                            <h2>11. Changes to Privacy Policy</h2>
                            <p>We may update this Privacy Policy from time to time. We will notify you of any material changes by posting the new Privacy Policy on our website and updating the "Last updated" date.</p>
                            <p>We encourage you to review this Privacy Policy periodically for any changes.</p>
                        </div>

                        <div class="legal-section" id="contact">
                            <h2>12. Contact Us</h2>
                            <p>If you have questions or concerns about this Privacy Policy or our data practices, please contact us:</p>
                            <div class="contact-legal">
                                <h3 class="h4">GlobalSkyFleet Data Protection Officer</h3>
                                <p class="mb-1">Email: privacy@globalskyfleet.com</p>
                                <p class="mb-1">Phone: +1-800-759-3533 (Extension 3)</p>
                                <p class="mb-0">Address: 350 Fifth Avenue, Suite 701, New York, NY 10118</p>
                            </div>
                            <p class="mt-3">For residents of the European Union, you also have the right to lodge a complaint with your local data protection authority.</p>
                        </div>

                        <!-- Last Updated -->
                        <div class="last-updated">
                            <p class="mb-0"><strong>Last Updated:</strong> {{ date('F d, Y') }}</p>
                            <p class="mb-0 small text-muted">This Privacy Policy replaces all previous versions.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Smooth scrolling for table of contents links
    document.addEventListener('DOMContentLoaded', function() {
        const tocLinks = document.querySelectorAll('.toc-list a');
        
        tocLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>
@endpush