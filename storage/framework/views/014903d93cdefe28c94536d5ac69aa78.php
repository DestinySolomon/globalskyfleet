

<?php $__env->startSection('title', 'Add New Address | GlobalSkyFleet'); ?>
<?php $__env->startSection('page-title', 'Add New Address'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-semibold">
                    <i class="ri-add-line me-2"></i>Add New Address
                </h5>
            </div>
            
            <div class="card-body">
                <form action="<?php echo e(route('addresses.store')); ?>" method="POST" id="addressForm">
                    <?php echo csrf_field(); ?>
                    
                    <!-- Address Type -->
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="ri-map-pin-line me-2"></i>Address Type
                        </h6>
                        <div class="row g-3">
                            <?php $__currentLoopData = $addressTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-3">
                                <div class="form-check card-check">
                                    <input class="form-check-input" type="radio" 
                                           name="type" id="type_<?php echo e($key); ?>" 
                                           value="<?php echo e($key); ?>" 
                                           <?php echo e(old('type') == $key ? 'checked' : ($key == 'shipping' ? 'checked' : '')); ?>

                                           required>
                                    <label class="form-check-label w-100" for="type_<?php echo e($key); ?>">
                                        <div class="card border h-100">
                                            <div class="card-body text-center py-3">
                                                <?php if($key === 'shipping'): ?>
                                                    <i class="ri-truck-line text-primary mb-2" style="font-size: 1.5rem;"></i>
                                                <?php elseif($key === 'billing'): ?>
                                                    <i class="ri-bill-line text-success mb-2" style="font-size: 1.5rem;"></i>
                                                <?php elseif($key === 'home'): ?>
                                                    <i class="ri-home-line text-warning mb-2" style="font-size: 1.5rem;"></i>
                                                <?php else: ?>
                                                    <i class="ri-building-line text-info mb-2" style="font-size: 1.5rem;"></i>
                                                <?php endif; ?>
                                                <div class="fw-semibold"><?php echo e($label); ?></div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="ri-user-line me-2"></i>Contact Information
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Contact Name *</label>
                                <input type="text" name="contact_name" class="form-control" 
                                       value="<?php echo e(old('contact_name')); ?>" 
                                       placeholder="John Doe" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number *</label>
                                <input type="tel" name="contact_phone" class="form-control" 
                                       value="<?php echo e(old('contact_phone')); ?>" 
                                       placeholder="+1 (555) 123-4567" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Company (Optional)</label>
                                <input type="text" name="company" class="form-control" 
                                       value="<?php echo e(old('company')); ?>" 
                                       placeholder="Company Name">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Address Details -->
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="ri-home-line me-2"></i>Address Details
                        </h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Address Line 1 *</label>
                                <input type="text" name="address_line1" class="form-control" 
                                       value="<?php echo e(old('address_line1')); ?>" 
                                       placeholder="Street address, P.O. box" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address Line 2 (Optional)</label>
                                <input type="text" name="address_line2" class="form-control" 
                                       value="<?php echo e(old('address_line2')); ?>" 
                                       placeholder="Apartment, suite, unit, building, floor">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City *</label>
                                <input type="text" name="city" class="form-control" 
                                       value="<?php echo e(old('city')); ?>" 
                                       placeholder="City" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">State/Province *</label>
                                <input type="text" name="state" class="form-control" 
                                       value="<?php echo e(old('state')); ?>" 
                                       placeholder="State" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Postal Code *</label>
                                <input type="text" name="postal_code" class="form-control" 
                                       value="<?php echo e(old('postal_code')); ?>" 
                                       placeholder="ZIP/Postal code" required>
                            </div>
                            <!-- Country Search Input -->
                            <div class="col-12">
                                <label class="form-label">Country *</label>
                                <div class="country-search-wrapper">
                                    <input type="text" 
                                           id="countrySearch" 
                                           class="form-control <?php $__errorArgs = ['country_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           placeholder="Type to search for a country..." 
                                           autocomplete="off"
                                           aria-autocomplete="list"
                                           aria-expanded="false"
                                           required>
                                    <input type="hidden" 
                                           name="country_code" 
                                           id="countryCode" 
                                           value="<?php echo e(old('country_code')); ?>">
                                    
                                    <!-- Dropdown for search results -->
                                    <div id="countryDropdown" class="country-dropdown" aria-hidden="true">
                                        <div id="countryList"></div>
                                    </div>
                                    
                                    <div class="invalid-feedback" id="countryError" style="display: none;">
                                        Please select a valid country from the list
                                    </div>
                                    <?php $__errorArgs = ['country_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <small class="text-muted">Start typing and select from dropdown</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Default Address Option -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="is_default" id="is_default" value="1"
                                   <?php echo e(old('is_default') ? 'checked' : ''); ?>>
                            <label class="form-check-label fw-semibold" for="is_default">
                                <i class="ri-star-line me-2"></i>Set as default address for this type
                            </label>
                            <div class="form-text">
                                This address will be automatically selected when creating shipments
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between pt-3 border-top">
                        <a href="<?php echo e(route('addresses.index')); ?>" class="btn btn-outline-secondary">
                            <i class="ri-arrow-left-line me-2"></i>Back to Address Book
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-2"></i>Save Address
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Help Card -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <h6 class="mb-3">
                    <i class="ri-question-line me-2"></i>Address Guidelines
                </h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="ri-information-line text-info me-2"></i>
                        Use the complete address for accurate delivery
                    </li>
                    <li class="mb-2">
                        <i class="ri-information-line text-info me-2"></i>
                        Include apartment/suite numbers in Address Line 2
                    </li>
                    <li class="mb-2">
                        <i class="ri-information-line text-info me-2"></i>
                        Phone number is required for delivery coordination
                    </li>
                    <li>
                        <i class="ri-information-line text-info me-2"></i>
                        Set a default address for faster checkout
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.card-check .form-check-input {
    position: absolute;
    opacity: 0;
}

.card-check .form-check-label .card {
    cursor: pointer;
    transition: all 0.2s;
    border: 2px solid transparent;
}

.card-check .form-check-input:checked + .form-check-label .card {
    border-color: #0a2463;
    background-color: rgba(10, 36, 99, 0.05);
}

.card-check .form-check-input:focus + .form-check-label .card {
    box-shadow: 0 0 0 0.25rem rgba(10, 36, 99, 0.25);
}

/* Country Search Styles - FIXED */
.country-search-wrapper {
    position: relative;
}

#countrySearch {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236c757d' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
    padding-right: 2.5rem;
    cursor: pointer;
}

#countrySearch:focus {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%230a2463' class='bi bi-chevron-up' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z'/%3E%3C/svg%3E");
}

.country-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    max-height: 300px;
    overflow-y: auto;
    background: white;
    border: 1px solid #dee2e6;
    border-top: none;
    border-radius: 0 0 0.375rem 0.375rem;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    z-index: 1060;
    margin-top: -1px;
}

.country-dropdown.show {
    display: block !important;
}

.country-item {
    padding: 10px 15px;
    cursor: pointer;
    transition: all 0.15s;
    border-bottom: 1px solid #f8f9fa;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.country-item:last-child {
    border-bottom: none;
}

.country-item:hover,
.country-item.active {
    background-color: #f8f9fa;
    border-left: 3px solid #0a2463;
    padding-left: 12px;
}

.country-item.no-results {
    cursor: default;
    color: #6c757d;
    background-color: white !important;
    border-left: none !important;
    padding-left: 15px !important;
}

.country-name {
    flex: 1;
    font-weight: 500;
}

.country-code {
    font-size: 0.75rem;
    color: #6c757d;
    background: #f8f9fa;
    padding: 3px 8px;
    border-radius: 4px;
    font-weight: 600;
    margin-left: 10px;
}

/* Ensure dropdown appears above other elements */
.form-control:focus {
    position: relative;
    z-index: 2;
}

/* Remove default browser autocomplete styling */
input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus {
    -webkit-box-shadow: 0 0 0px 1000px white inset !important;
    box-shadow: 0 0 0px 1000px white inset !important;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Country search script initialized');
    
    // Comprehensive list of countries with their codes
    const countries = [
        { code: 'AF', name: 'Afghanistan' },
        { code: 'AL', name: 'Albania' },
        { code: 'DZ', name: 'Algeria' },
        { code: 'AS', name: 'American Samoa' },
        { code: 'AD', name: 'Andorra' },
        { code: 'AO', name: 'Angola' },
        { code: 'AI', name: 'Anguilla' },
        { code: 'AG', name: 'Antigua and Barbuda' },
        { code: 'AR', name: 'Argentina' },
        { code: 'AM', name: 'Armenia' },
        { code: 'AW', name: 'Aruba' },
        { code: 'AU', name: 'Australia' },
        { code: 'AT', name: 'Austria' },
        { code: 'AZ', name: 'Azerbaijan' },
        { code: 'BS', name: 'Bahamas' },
        { code: 'BH', name: 'Bahrain' },
        { code: 'BD', name: 'Bangladesh' },
        { code: 'BB', name: 'Barbados' },
        { code: 'BY', name: 'Belarus' },
        { code: 'BE', name: 'Belgium' },
        { code: 'BZ', name: 'Belize' },
        { code: 'BJ', name: 'Benin' },
        { code: 'BM', name: 'Bermuda' },
        { code: 'BT', name: 'Bhutan' },
        { code: 'BO', name: 'Bolivia' },
        { code: 'BA', name: 'Bosnia and Herzegovina' },
        { code: 'BW', name: 'Botswana' },
        { code: 'BR', name: 'Brazil' },
        { code: 'BN', name: 'Brunei' },
        { code: 'BG', name: 'Bulgaria' },
        { code: 'BF', name: 'Burkina Faso' },
        { code: 'BI', name: 'Burundi' },
        { code: 'KH', name: 'Cambodia' },
        { code: 'CM', name: 'Cameroon' },
        { code: 'CA', name: 'Canada' },
        { code: 'CV', name: 'Cape Verde' },
        { code: 'KY', name: 'Cayman Islands' },
        { code: 'CF', name: 'Central African Republic' },
        { code: 'TD', name: 'Chad' },
        { code: 'CL', name: 'Chile' },
        { code: 'CN', name: 'China' },
        { code: 'CO', name: 'Colombia' },
        { code: 'KM', name: 'Comoros' },
        { code: 'CG', name: 'Congo' },
        { code: 'CD', name: 'Congo, Democratic Republic' },
        { code: 'CK', name: 'Cook Islands' },
        { code: 'CR', name: 'Costa Rica' },
        { code: 'HR', name: 'Croatia' },
        { code: 'CU', name: 'Cuba' },
        { code: 'CY', name: 'Cyprus' },
        { code: 'CZ', name: 'Czech Republic' },
        { code: 'DK', name: 'Denmark' },
        { code: 'DJ', name: 'Djibouti' },
        { code: 'DM', name: 'Dominica' },
        { code: 'DO', name: 'Dominican Republic' },
        { code: 'EC', name: 'Ecuador' },
        { code: 'EG', name: 'Egypt' },
        { code: 'SV', name: 'El Salvador' },
        { code: 'GQ', name: 'Equatorial Guinea' },
        { code: 'ER', name: 'Eritrea' },
        { code: 'EE', name: 'Estonia' },
        { code: 'ET', name: 'Ethiopia' },
        { code: 'FK', name: 'Falkland Islands' },
        { code: 'FO', name: 'Faroe Islands' },
        { code: 'FJ', name: 'Fiji' },
        { code: 'FI', name: 'Finland' },
        { code: 'FR', name: 'France' },
        { code: 'GF', name: 'French Guiana' },
        { code: 'PF', name: 'French Polynesia' },
        { code: 'GA', name: 'Gabon' },
        { code: 'GM', name: 'Gambia' },
        { code: 'GE', name: 'Georgia' },
        { code: 'DE', name: 'Germany' },
        { code: 'GH', name: 'Ghana' },
        { code: 'GI', name: 'Gibraltar' },
        { code: 'GR', name: 'Greece' },
        { code: 'GL', name: 'Greenland' },
        { code: 'GD', name: 'Grenada' },
        { code: 'GP', name: 'Guadeloupe' },
        { code: 'GU', name: 'Guam' },
        { code: 'GT', name: 'Guatemala' },
        { code: 'GN', name: 'Guinea' },
        { code: 'GW', name: 'Guinea-Bissau' },
        { code: 'GY', name: 'Guyana' },
        { code: 'HT', name: 'Haiti' },
        { code: 'HN', name: 'Honduras' },
        { code: 'HK', name: 'Hong Kong' },
        { code: 'HU', name: 'Hungary' },
        { code: 'IS', name: 'Iceland' },
        { code: 'IN', name: 'India' },
        { code: 'ID', name: 'Indonesia' },
        { code: 'IR', name: 'Iran' },
        { code: 'IQ', name: 'Iraq' },
        { code: 'IE', name: 'Ireland' },
        { code: 'IL', name: 'Israel' },
        { code: 'IT', name: 'Italy' },
        { code: 'CI', name: 'Ivory Coast' },
        { code: 'JM', name: 'Jamaica' },
        { code: 'JP', name: 'Japan' },
        { code: 'JO', name: 'Jordan' },
        { code: 'KZ', name: 'Kazakhstan' },
        { code: 'KE', name: 'Kenya' },
        { code: 'KI', name: 'Kiribati' },
        { code: 'KP', name: 'Korea, North' },
        { code: 'KR', name: 'Korea, South' },
        { code: 'KW', name: 'Kuwait' },
        { code: 'KG', name: 'Kyrgyzstan' },
        { code: 'LA', name: 'Laos' },
        { code: 'LV', name: 'Latvia' },
        { code: 'LB', name: 'Lebanon' },
        { code: 'LS', name: 'Lesotho' },
        { code: 'LR', name: 'Liberia' },
        { code: 'LY', name: 'Libya' },
        { code: 'LI', name: 'Liechtenstein' },
        { code: 'LT', name: 'Lithuania' },
        { code: 'LU', name: 'Luxembourg' },
        { code: 'MO', name: 'Macau' },
        { code: 'MK', name: 'Macedonia' },
        { code: 'MG', name: 'Madagascar' },
        { code: 'MW', name: 'Malawi' },
        { code: 'MY', name: 'Malaysia' },
        { code: 'MV', name: 'Maldives' },
        { code: 'ML', name: 'Mali' },
        { code: 'MT', name: 'Malta' },
        { code: 'MH', name: 'Marshall Islands' },
        { code: 'MQ', name: 'Martinique' },
        { code: 'MR', name: 'Mauritania' },
        { code: 'MU', name: 'Mauritius' },
        { code: 'MX', name: 'Mexico' },
        { code: 'FM', name: 'Micronesia' },
        { code: 'MD', name: 'Moldova' },
        { code: 'MC', name: 'Monaco' },
        { code: 'MN', name: 'Mongolia' },
        { code: 'ME', name: 'Montenegro' },
        { code: 'MS', name: 'Montserrat' },
        { code: 'MA', name: 'Morocco' },
        { code: 'MZ', name: 'Mozambique' },
        { code: 'MM', name: 'Myanmar' },
        { code: 'NA', name: 'Namibia' },
        { code: 'NR', name: 'Nauru' },
        { code: 'NP', name: 'Nepal' },
        { code: 'NL', name: 'Netherlands' },
        { code: 'NC', name: 'New Caledonia' },
        { code: 'NZ', name: 'New Zealand' },
        { code: 'NI', name: 'Nicaragua' },
        { code: 'NE', name: 'Niger' },
        { code: 'NG', name: 'Nigeria' },
        { code: 'NO', name: 'Norway' },
        { code: 'OM', name: 'Oman' },
        { code: 'PK', name: 'Pakistan' },
        { code: 'PW', name: 'Palau' },
        { code: 'PS', name: 'Palestine' },
        { code: 'PA', name: 'Panama' },
        { code: 'PG', name: 'Papua New Guinea' },
        { code: 'PY', name: 'Paraguay' },
        { code: 'PE', name: 'Peru' },
        { code: 'PH', name: 'Philippines' },
        { code: 'PL', name: 'Poland' },
        { code: 'PT', name: 'Portugal' },
        { code: 'PR', name: 'Puerto Rico' },
        { code: 'QA', name: 'Qatar' },
        { code: 'RE', name: 'Reunion' },
        { code: 'RO', name: 'Romania' },
        { code: 'RU', name: 'Russia' },
        { code: 'RW', name: 'Rwanda' },
        { code: 'WS', name: 'Samoa' },
        { code: 'SM', name: 'San Marino' },
        { code: 'ST', name: 'Sao Tome and Principe' },
        { code: 'SA', name: 'Saudi Arabia' },
        { code: 'SN', name: 'Senegal' },
        { code: 'RS', name: 'Serbia' },
        { code: 'SC', name: 'Seychelles' },
        { code: 'SL', name: 'Sierra Leone' },
        { code: 'SG', name: 'Singapore' },
        { code: 'SK', name: 'Slovakia' },
        { code: 'SI', name: 'Slovenia' },
        { code: 'SB', name: 'Solomon Islands' },
        { code: 'SO', name: 'Somalia' },
        { code: 'ZA', name: 'South Africa' },
        { code: 'SS', name: 'South Sudan' },
        { code: 'ES', name: 'Spain' },
        { code: 'LK', name: 'Sri Lanka' },
        { code: 'SD', name: 'Sudan' },
        { code: 'SR', name: 'Suriname' },
        { code: 'SZ', name: 'Swaziland' },
        { code: 'SE', name: 'Sweden' },
        { code: 'CH', name: 'Switzerland' },
        { code: 'SY', name: 'Syria' },
        { code: 'TW', name: 'Taiwan' },
        { code: 'TJ', name: 'Tajikistan' },
        { code: 'TZ', name: 'Tanzania' },
        { code: 'TH', name: 'Thailand' },
        { code: 'TL', name: 'Timor-Leste' },
        { code: 'TG', name: 'Togo' },
        { code: 'TO', name: 'Tonga' },
        { code: 'TT', name: 'Trinidad and Tobago' },
        { code: 'TN', name: 'Tunisia' },
        { code: 'TR', name: 'Turkey' },
        { code: 'TM', name: 'Turkmenistan' },
        { code: 'TV', name: 'Tuvalu' },
        { code: 'UG', name: 'Uganda' },
        { code: 'UA', name: 'Ukraine' },
        { code: 'AE', name: 'United Arab Emirates' },
        { code: 'GB', name: 'United Kingdom' },
        { code: 'US', name: 'United States' },
        { code: 'UY', name: 'Uruguay' },
        { code: 'UZ', name: 'Uzbekistan' },
        { code: 'VU', name: 'Vanuatu' },
        { code: 'VA', name: 'Vatican City' },
        { code: 'VE', name: 'Venezuela' },
        { code: 'VN', name: 'Vietnam' },
        { code: 'VG', name: 'Virgin Islands, British' },
        { code: 'VI', name: 'Virgin Islands, US' },
        { code: 'YE', name: 'Yemen' },
        { code: 'ZM', name: 'Zambia' },
        { code: 'ZW', name: 'Zimbabwe' }
    ];

    const countrySearch = document.getElementById('countrySearch');
    const countryCode = document.getElementById('countryCode');
    const countryDropdown = document.getElementById('countryDropdown');
    const countryList = document.getElementById('countryList');
    const countryError = document.getElementById('countryError');
    const addressForm = document.getElementById('addressForm');
    
    let selectedIndex = -1;
    let filteredCountries = [];

    // Initialize with existing country if there's old input
    if (countryCode && countryCode.value) {
        const existingCountry = countries.find(c => c.code === countryCode.value);
        if (existingCountry) {
            countrySearch.value = existingCountry.name;
        }
    }

    // Function to show dropdown
    function showDropdown() {
        countryDropdown.classList.add('show');
        countryDropdown.setAttribute('aria-hidden', 'false');
        countrySearch.setAttribute('aria-expanded', 'true');
    }

    // Function to hide dropdown
    function hideDropdown() {
        countryDropdown.classList.remove('show');
        countryDropdown.setAttribute('aria-hidden', 'true');
        countrySearch.setAttribute('aria-expanded', 'false');
        selectedIndex = -1;
    }

    // Display filtered countries
    function displayCountries(countriesToDisplay) {
        countryList.innerHTML = '';
        
        if (countriesToDisplay.length === 0) {
            countryList.innerHTML = '<div class="country-item no-results">No countries found. Try a different search.</div>';
            return;
        }
        
        countriesToDisplay.forEach((country, index) => {
            const div = document.createElement('div');
            div.className = 'country-item';
            div.setAttribute('role', 'option');
            div.setAttribute('aria-selected', 'false');
            div.innerHTML = `
                <span class="country-name">${country.name}</span>
                <span class="country-code">${country.code}</span>
            `;
            div.dataset.code = country.code;
            div.dataset.name = country.name;
            div.dataset.index = index;
            
            div.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                selectCountry(country);
            });
            
            div.addEventListener('mouseenter', function() {
                setSelectedIndex(index);
            });
            
            countryList.appendChild(div);
        });
    }

    // Set selected index and update UI
    function setSelectedIndex(index) {
        selectedIndex = index;
        updateSelection();
    }

    // Update visual selection
    function updateSelection() {
        const items = countryList.querySelectorAll('.country-item');
        items.forEach((item, index) => {
            const isSelected = index === selectedIndex;
            item.classList.toggle('active', isSelected);
            item.setAttribute('aria-selected', isSelected);
            
            if (isSelected) {
                item.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
            }
        });
    }

    // Select country
    function selectCountry(country) {
        countrySearch.value = country.name;
        countryCode.value = country.code;
        hideDropdown();
        countryError.style.display = 'none';
        countrySearch.classList.remove('is-invalid');
        console.log('Selected country:', country.name, 'Code:', country.code);
    }

    // Search functionality with debounce
    let searchTimeout;
    countrySearch.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        
        searchTimeout = setTimeout(() => {
            const searchTerm = this.value.toLowerCase().trim();
            
            if (searchTerm.length === 0) {
                hideDropdown();
                countryCode.value = '';
                countryError.style.display = 'none';
                return;
            }

            // Filter countries
            filteredCountries = countries.filter(country => 
                country.name.toLowerCase().includes(searchTerm) ||
                country.code.toLowerCase().includes(searchTerm)
            );

            displayCountries(filteredCountries);
            showDropdown();
            selectedIndex = -1;
        }, 200);
    });

    // Keyboard navigation
    countrySearch.addEventListener('keydown', function(e) {
        if (!countryDropdown.classList.contains('show')) {
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                // Show dropdown if hidden and arrow keys pressed
                const searchTerm = this.value.toLowerCase().trim();
                if (searchTerm.length > 0) {
                    filteredCountries = countries.filter(country => 
                        country.name.toLowerCase().includes(searchTerm)
                    );
                    if (filteredCountries.length > 0) {
                        displayCountries(filteredCountries);
                        showDropdown();
                        selectedIndex = e.key === 'ArrowDown' ? 0 : filteredCountries.length - 1;
                        updateSelection();
                    }
                }
            }
            return;
        }
        
        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                if (filteredCountries.length > 0) {
                    selectedIndex = (selectedIndex + 1) % filteredCountries.length;
                    updateSelection();
                }
                break;
                
            case 'ArrowUp':
                e.preventDefault();
                if (filteredCountries.length > 0) {
                    selectedIndex = selectedIndex <= 0 ? filteredCountries.length - 1 : selectedIndex - 1;
                    updateSelection();
                }
                break;
                
            case 'Enter':
                e.preventDefault();
                if (selectedIndex >= 0 && selectedIndex < filteredCountries.length) {
                    selectCountry(filteredCountries[selectedIndex]);
                } else if (filteredCountries.length === 1) {
                    // If only one result, select it
                    selectCountry(filteredCountries[0]);
                }
                break;
                
            case 'Escape':
                e.preventDefault();
                hideDropdown();
                break;
                
            case 'Tab':
                // Allow tab to work normally
                hideDropdown();
                break;
        }
    });

    // Show dropdown on focus if there's content
    countrySearch.addEventListener('focus', function() {
        const searchTerm = this.value.toLowerCase().trim();
        if (searchTerm.length > 0) {
            filteredCountries = countries.filter(country => 
                country.name.toLowerCase().includes(searchTerm)
            );
            if (filteredCountries.length > 0) {
                displayCountries(filteredCountries);
                showDropdown();
            }
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!countrySearch.contains(e.target) && !countryDropdown.contains(e.target)) {
            hideDropdown();
            
            // Validate on click outside
            if (countrySearch.value && !countryCode.value) {
                countryError.style.display = 'block';
                countrySearch.classList.add('is-invalid');
            }
        }
    });

    // Form validation
    if (addressForm) {
        addressForm.addEventListener('submit', function(e) {
            if (!countryCode.value) {
                e.preventDefault();
                countrySearch.classList.add('is-invalid');
                countryError.style.display = 'block';
                countrySearch.focus();
                
                // Show dropdown with all countries if empty
                if (!countrySearch.value) {
                    filteredCountries = countries;
                    displayCountries(filteredCountries);
                    showDropdown();
                }
                
                countrySearch.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }
        });
    }

    // Phone number formatting
    const phoneInput = document.querySelector('input[name="contact_phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length > 0) {
                value = '+' + value;
                if (value.length > 3) {
                    value = value.substring(0, 3) + ' ' + value.substring(3);
                }
                if (value.length > 7) {
                    value = value.substring(0, 7) + ' ' + value.substring(7);
                }
                if (value.length > 11) {
                    value = value.substring(0, 11) + ' ' + value.substring(11);
                }
            }
            
            e.target.value = value;
        });
    }

    // Sort countries alphabetically for better UX
    countries.sort((a, b) => a.name.localeCompare(b.name));
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views\addresses\create.blade.php ENDPATH**/ ?>