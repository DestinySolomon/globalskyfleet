@extends('layouts.app')

@section('title', 'Terms of Service | GlobalSkyFleet')

@section('description', 'GlobalSkyFleet Terms of Service - Please read these terms carefully before using our international shipping and logistics services.')

@section('keywords', 'terms of service, terms and conditions, shipping terms, logistics terms, service agreement')

@section('content')
    <!-- Hero Section -->
    <section class="legal-hero">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="legal-icon">
                        <i class="ri-file-text-line"></i>
                    </div>
                    <h1 class="display-4 fw-bold text-white mb-4">Terms of Service</h1>
                    <p class="text-white opacity-80 fs-5 mb-0">
                        Last updated: {{ date('F d, Y') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Terms Content -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="legal-content">
                        <!-- Table of Contents -->
                        <div class="toc-container">
                            <h3 class="h4 fw-bold text-navy mb-3">Table of Contents</h3>
                            <ul class="toc-list">
                                <li><a href="#acceptance">1. Acceptance of Terms</a></li>
                                <li><a href="#services">2. Our Services</a></li>
                                <li><a href="#registration">3. Registration & Account</a></li>
                                <li><a href="#shipping">4. Shipping Terms</a></li>
                                <li><a href="#payments">5. Payments & Fees</a></li>
                                <li><a href="#prohibited">6. Prohibited Items</a></li>
                                <li><a href="#liability">7. Liability & Limitations</a></li>
                                <li><a href="#intellectual">8. Intellectual Property</a></li>
                                <li><a href="#termination">9. Termination</a></li>
                                <li><a href="#changes">10. Changes to Terms</a></li>
                                <li><a href="#governing">11. Governing Law</a></li>
                                <li><a href="#contact">12. Contact Information</a></li>
                            </ul>
                        </div>

                        <!-- Legal Content -->
                        <div class="legal-section" id="acceptance">
                            <h2>1. Acceptance of Terms</h2>
                            <p>By accessing or using the GlobalSkyFleet website, mobile application, or any of our services, you agree to be bound by these Terms of Service ("Terms"). If you do not agree to all of these Terms, you may not access or use our services.</p>
                            <p>These Terms constitute a legally binding agreement between you and GlobalSkyFleet Logistics Inc. ("GlobalSkyFleet," "we," "us," or "our").</p>
                        </div>

                        <div class="legal-section" id="services">
                            <h2>2. Our Services</h2>
                            <p>GlobalSkyFleet provides international shipping, logistics, and courier services including but not limited to:</p>
                            <ul>
                                <li>Air freight services</li>
                                <li>Sea freight services</li>
                                <li>Express delivery services</li>
                                <li>Road courier services</li>
                                <li>Warehousing and distribution</li>
                                <li>Customs clearance services</li>
                                <li>Real-time shipment tracking</li>
                            </ul>
                            <p>We reserve the right to modify, suspend, or discontinue any part of our services at any time without prior notice.</p>
                        </div>

                        <div class="legal-section" id="registration">
                            <h2>3. Registration & Account</h2>
                            <p>To use certain features of our services, you must register for an account. You agree to:</p>
                            <ul>
                                <li>Provide accurate, current, and complete information during registration</li>
                                <li>Maintain and promptly update your account information</li>
                                <li>Maintain the security of your password and accept all risks of unauthorized access</li>
                                <li>Notify us immediately of any unauthorized use of your account</li>
                                <li>Be responsible for all activities that occur under your account</li>
                            </ul>
                            <p>You must be at least 18 years old to create an account and use our services.</p>
                        </div>

                        <div class="legal-section" id="shipping">
                            <h2>4. Shipping Terms</h2>
                            <h3>4.1 Shipment Preparation</h3>
                            <p>You are responsible for properly packaging your shipments according to our packaging guidelines. We are not liable for damage caused by improper packaging.</p>
                            
                            <h3>4.2 Documentation</h3>
                            <p>You must provide accurate and complete documentation for all shipments, including commercial invoices, packing lists, and any required customs declarations.</p>
                            
                            <h3>4.3 Customs & Duties</h3>
                            <p>You are responsible for all customs duties, taxes, and fees associated with your shipments. We may act as your agent for customs clearance, but all customs-related decisions remain your responsibility.</p>
                            
                            <h3>4.4 Delivery Times</h3>
                            <p>Estimated delivery times are provided for guidance only and are not guaranteed. We are not liable for delays caused by circumstances beyond our control.</p>
                        </div>

                        <div class="legal-section" id="payments">
                            <h2>5. Payments & Fees</h2>
                            <p>All fees are due at the time of shipment unless you have an approved credit account with us. We accept various payment methods including credit cards, debit cards, and wire transfers.</p>
                            <p>Prices are subject to change without notice. Additional charges may apply for:</p>
                            <ul>
                                <li>Additional handling requirements</li>
                                <li>Remote area deliveries</li>
                                <li>Storage fees for undeliverable packages</li>
                                <li>Customs duties and taxes</li>
                                <li>Fuel surcharges</li>
                            </ul>
                        </div>

                        <div class="legal-section" id="prohibited">
                            <h2>6. Prohibited Items</h2>
                            <p>You may not ship prohibited items through our services, including but not limited to:</p>
                            <ul>
                                <li>Illegal drugs and narcotics</li>
                                <li>Weapons, firearms, and ammunition</li>
                                <li>Explosives and hazardous materials</li>
                                <li>Perishable food items requiring refrigeration</li>
                                <li>Live animals</li>
                                <li>Currency and negotiable instruments</li>
                                <li>Pornographic materials</li>
                                <li>Counterfeit goods</li>
                            </ul>
                            <p>We reserve the right to inspect any shipment and refuse service for any reason.</p>
                        </div>

                        <div class="legal-section" id="liability">
                            <h2>7. Liability & Limitations</h2>
                            <h3>7.1 Liability Limit</h3>
                            <p>Our liability for loss or damage to shipments is limited to the lesser of:</p>
                            <ul>
                                <li>The actual value of the lost or damaged goods</li>
                                <li>$100 per shipment, unless you declare a higher value and pay additional charges</li>
                            </ul>
                            
                            <h3>7.2 Excluded Damages</h3>
                            <p>We are not liable for any indirect, incidental, special, consequential, or punitive damages, including lost profits or revenue.</p>
                            
                            <h3>7.3 Claims Procedure</h3>
                            <p>Claims for lost or damaged shipments must be filed within 30 days of the delivery date or the expected delivery date for lost shipments.</p>
                        </div>

                        <div class="legal-section" id="intellectual">
                            <h2>8. Intellectual Property</h2>
                            <p>All content on our website and mobile applications, including text, graphics, logos, and software, is the property of GlobalSkyFleet and is protected by copyright and other intellectual property laws.</p>
                            <p>You may not use our trademarks, logos, or service marks without our prior written permission.</p>
                        </div>

                        <div class="legal-section" id="termination">
                            <h2>9. Termination</h2>
                            <p>We may terminate or suspend your account and access to our services at any time, without notice, for conduct that we believe violates these Terms or is harmful to other users, us, or third parties.</p>
                            <p>You may terminate your account at any time by contacting our customer support.</p>
                        </div>

                        <div class="legal-section" id="changes">
                            <h2>10. Changes to Terms</h2>
                            <p>We reserve the right to modify these Terms at any time. We will notify you of material changes by posting the new Terms on our website and updating the "Last updated" date.</p>
                            <p>Your continued use of our services after such changes constitutes your acceptance of the new Terms.</p>
                        </div>

                        <div class="legal-section" id="governing">
                            <h2>11. Governing Law</h2>
                            <p>These Terms shall be governed by and construed in accordance with the laws of the State of Delaware, United States, without regard to its conflict of law provisions.</p>
                            <p>Any disputes arising from these Terms or your use of our services shall be resolved in the state or federal courts located in Delaware.</p>
                        </div>

                        <div class="legal-section" id="contact">
                            <h2>12. Contact Information</h2>
                            <p>If you have any questions about these Terms, please contact us:</p>
                            <div class="contact-legal">
                                <h3 class="h4">GlobalSkyFleet Legal Department</h3>
                                <p class="mb-1">Email: legal@globalskyfleet.com</p>
                                <p class="mb-1">Phone: +1-800-759-3533 (Extension 2)</p>
                                <p class="mb-0">Address: 350 Fifth Avenue, Suite 701, New York, NY 10118</p>
                            </div>
                        </div>

                        <!-- Last Updated -->
                        <div class="last-updated">
                            <p class="mb-0"><strong>Last Updated:</strong> {{ date('F d, Y') }}</p>
                            <p class="mb-0 small text-muted">These Terms of Service replace all previous versions.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection