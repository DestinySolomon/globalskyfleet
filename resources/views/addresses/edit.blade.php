@extends('layouts.dashboard')

@section('title', 'Edit Address | GlobalSkyFleet')
@section('page-title', 'Edit Address')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Header with Back Button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <a href="{{ route('addresses.show', $address) }}" class="btn btn-outline-secondary btn-sm me-3">
                    <i class="ri-arrow-left-line"></i>
                </a>
                <div>
                    <h4 class="mb-0">Edit Address</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('addresses.index') }}">Address Book</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('addresses.show', $address) }}">{{ $address->contact_name }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <a href="{{ route('addresses.show', $address) }}" class="btn btn-outline-secondary">
                    <i class="ri-close-line me-2"></i>Cancel
                </a>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="ri-delete-bin-line me-2"></i>Delete
                </button>
            </div>
        </div>

        <!-- Edit Form Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-semibold">
                    <i class="ri-edit-line me-2"></i>Edit Address Details
                </h5>
            </div>
            
            <div class="card-body">
                <form action="{{ route('addresses.update', $address) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Address Type -->
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="ri-map-pin-line me-2"></i>Address Type
                        </h6>
                        <div class="row g-3">
                            @foreach($addressTypes as $key => $label)
                            <div class="col-md-3">
                                <div class="form-check card-check">
                                    <input class="form-check-input" type="radio" 
                                           name="type" id="type_{{ $key }}" 
                                           value="{{ $key }}" 
                                           {{ old('type', $address->type) == $key ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label w-100" for="type_{{ $key }}">
                                        <div class="card border h-100">
                                            <div class="card-body text-center py-3">
                                                @if($key === 'shipping')
                                                    <i class="ri-truck-line text-primary mb-2" style="font-size: 1.5rem;"></i>
                                                @elseif($key === 'billing')
                                                    <i class="ri-bill-line text-success mb-2" style="font-size: 1.5rem;"></i>
                                                @elseif($key === 'home')
                                                    <i class="ri-home-line text-warning mb-2" style="font-size: 1.5rem;"></i>
                                                @else
                                                    <i class="ri-building-line text-info mb-2" style="font-size: 1.5rem;"></i>
                                                @endif
                                                <div class="fw-semibold">{{ $label }}</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="ri-user-line me-2"></i>Contact Information
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Contact Name *</label>
                                <input type="text" name="contact_name" class="form-control @error('contact_name') is-invalid @enderror" 
                                       value="{{ old('contact_name', $address->contact_name) }}" 
                                       placeholder="John Doe" required>
                                @error('contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number *</label>
                                <input type="tel" name="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" 
                                       value="{{ old('contact_phone', $address->contact_phone) }}" 
                                       placeholder="+1 (555) 123-4567" required>
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Company (Optional)</label>
                                <input type="text" name="company" class="form-control @error('company') is-invalid @enderror" 
                                       value="{{ old('company', $address->company) }}" 
                                       placeholder="Company Name">
                                @error('company')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                <input type="text" name="address_line1" class="form-control @error('address_line1') is-invalid @enderror" 
                                       value="{{ old('address_line1', $address->address_line1) }}" 
                                       placeholder="Street address, P.O. box" required>
                                @error('address_line1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address Line 2 (Optional)</label>
                                <input type="text" name="address_line2" class="form-control @error('address_line2') is-invalid @enderror" 
                                       value="{{ old('address_line2', $address->address_line2) }}" 
                                       placeholder="Apartment, suite, unit, building, floor">
                                @error('address_line2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City *</label>
                                <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
                                       value="{{ old('city', $address->city) }}" 
                                       placeholder="City" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">State/Province *</label>
                                <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" 
                                       value="{{ old('state', $address->state) }}" 
                                       placeholder="State" required>
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Postal Code *</label>
                                <input type="text" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror" 
                                       value="{{ old('postal_code', $address->postal_code) }}" 
                                       placeholder="ZIP/Postal code" required>
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Country *</label>
                                <div class="position-relative">
                                    <input type="text" 
                                           id="countrySearch" 
                                           class="form-control @error('country_code') is-invalid @enderror" 
                                           placeholder="Search for a country..." 
                                           autocomplete="off"
                                           required>
                                    <input type="hidden" 
                                           name="country_code" 
                                           id="countryCode" 
                                           value="{{ old('country_code', $address->country_code) }}">
                                    
                                    <!-- Dropdown for search results -->
                                    <div id="countryDropdown" class="position-absolute w-100 bg-white border rounded shadow-sm" style="display: none; max-height: 300px; overflow-y: auto; z-index: 1000; top: 100%; left: 0; margin-top: 2px;">
                                        <div id="countryList"></div>
                                    </div>
                                    
                                    <div class="invalid-feedback" id="countryError" style="display: none;">
                                        Please select a valid country from the list
                                    </div>
                                    @error('country_code')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Default Address Option -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="is_default" id="is_default" value="1"
                                   {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_default">
                                <i class="ri-star-line me-2"></i>Set as default address for this type
                            </label>
                            <div class="form-text">
                                @if($address->is_default)
                                <span class="text-warning">
                                    <i class="ri-information-line me-1"></i>
                                    Currently set as default {{ $address->type }} address
                                </span>
                                @else
                                This address will be automatically selected when creating shipments
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Usage Warning -->
                    @php
                        $totalShipments = $address->senderShipments()->count() + $address->recipientShipments()->count();
                    @endphp
                    
                    @if($totalShipments > 0)
                    <div class="alert alert-warning mb-4">
                        <i class="ri-alert-line me-2"></i>
                        <strong>Note:</strong> This address is being used in <strong>{{ $totalShipments }}</strong> shipments.
                        Changes will be reflected in all associated shipments.
                    </div>
                    @endif
                    
                    <!-- Submit Button -->
                    <div class="border-top pt-4">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-2"></i>Update Address
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Address Preview Card -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <h6 class="mb-3">
                    <i class="ri-eye-line me-2"></i>Live Preview
                </h6>
                <div class="p-3 bg-light rounded">
                    <div class="row">
                        <div class="col-md-6">
                            <strong class="d-block mb-2">Contact Information</strong>
                            <div id="preview-contact">
                                {{ old('contact_name', $address->contact_name) }}<br>
                                {{ old('contact_phone', $address->contact_phone) }}<br>
                                @if(old('company', $address->company))
                                    {{ old('company', $address->company) }}<br>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <strong class="d-block mb-2">Address</strong>
                            <div id="preview-address">
                                {{ old('address_line1', $address->address_line1) }}<br>
                                @if(old('address_line2', $address->address_line2))
                                    {{ old('address_line2', $address->address_line2) }}<br>
                                @endif
                                {{ old('city', $address->city) }}, {{ old('state', $address->state) }} {{ old('postal_code', $address->postal_code) }}<br>
                                {{ old('country_code', $address->country_code) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="ri-alert-line me-2"></i>
                    <strong>Warning:</strong> Deleting this address cannot be undone.
                </div>
                <p>Are you sure you want to delete the following address?</p>
                <div class="p-3 bg-light rounded mb-3">
                    <strong>{{ $address->contact_name }}</strong><br>
                    {{ $address->address_line1 }}<br>
                    {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}
                </div>
                
                @if($totalShipments > 0)
                <div class="alert alert-info">
                    <i class="ri-information-line me-2"></i>
                    This address is being used in <strong>{{ $totalShipments }}</strong> shipments.
                    Deleting it may affect shipment tracking.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ri-delete-bin-line me-2"></i>Delete Address
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Updated CSS - Add this to your styles section -->
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

/* Fixed Country Dropdown Styles */
.country-search-wrapper {
    position: relative;
}

#countryDropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    margin-top: 2px;
    max-height: 300px;
    overflow-y: auto;
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    z-index: 1050 !important; /* Higher than Bootstrap modals */
}

.country-item {
    padding: 10px 15px;
    cursor: pointer;
    transition: background-color 0.2s;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.country-item:last-child {
    border-bottom: none;
}

.country-item:hover {
    background-color: #f8f9fa;
}

.country-item.active {
    background-color: #e3f2fd;
}

.country-code {
    font-size: 0.85rem;
    color: #6c757d;
    margin-left: 8px;
    font-weight: 500;
}

.country-name {
    flex: 1;
}
</style>

<!-- Updated Country Input HTML - Replace the country input section -->
<div class="col-12">
    <label class="form-label">Country *</label>
    <div class="country-search-wrapper">
        <input type="text" 
               id="countrySearch" 
               class="form-control @error('country_code') is-invalid @enderror" 
               placeholder="Search for a country..." 
               autocomplete="off"
               required>
        <input type="hidden" 
               name="country_code" 
               id="countryCode" 
               value="{{ old('country_code', $address->country_code) }}">
        
        <!-- Dropdown for search results -->
        <div id="countryDropdown" style="display: none;">
            <div id="countryList"></div>
        </div>
        
        <div class="invalid-feedback" id="countryError" style="display: none;">
            Please select a valid country from the list
        </div>
        @error('country_code')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- Updated JavaScript - Replace the entire script section -->
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
    
    let selectedIndex = -1;
    let filteredCountries = [];

    // Initialize with existing country if editing
    if (countryCode && countryCode.value) {
        const existingCountry = countries.find(c => c.code === countryCode.value);
        if (existingCountry) {
            countrySearch.value = existingCountry.name;
            console.log('Initialized with country:', existingCountry.name);
        }
    }

    // Search functionality
    countrySearch.addEventListener('input', function(e) {
        const searchTerm = this.value.toLowerCase().trim();
        console.log('Searching for:', searchTerm);
        
        if (searchTerm.length === 0) {
            countryDropdown.style.display = 'none';
            countryCode.value = '';
            countryError.style.display = 'none';
            return;
        }

        // Filter countries
        filteredCountries = countries.filter(country => 
            country.name.toLowerCase().includes(searchTerm) ||
            country.code.toLowerCase().includes(searchTerm)
        );

        console.log('Found countries:', filteredCountries.length);

        if (filteredCountries.length > 0) {
            displayCountries(filteredCountries);
            countryDropdown.style.display = 'block';
            console.log('Dropdown should be visible now');
            selectedIndex = -1;
        } else {
            countryList.innerHTML = '<div class="country-item text-muted" style="cursor: default;">No countries found</div>';
            countryDropdown.style.display = 'block';
        }
    });

    // Display filtered countries
    function displayCountries(countriesToDisplay) {
        countryList.innerHTML = '';
        
        countriesToDisplay.forEach((country, index) => {
            const div = document.createElement('div');
            div.className = 'country-item';
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
                selectedIndex = index;
                updateSelection();
            });
            
            countryList.appendChild(div);
        });
    }

    // Select country
    function selectCountry(country) {
        console.log('Selected country:', country.name);
        countrySearch.value = country.name;
        countryCode.value = country.code;
        countryDropdown.style.display = 'none';
        countryError.style.display = 'none';
        countrySearch.classList.remove('is-invalid');
        
        // Trigger preview update
        updatePreview();
    }

    // Update visual selection
    function updateSelection() {
        const items = countryList.querySelectorAll('.country-item');
        items.forEach((item, index) => {
            if (index === selectedIndex) {
                item.classList.add('active');
                item.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
            } else {
                item.classList.remove('active');
            }
        });
    }

    // Keyboard navigation
    countrySearch.addEventListener('keydown', function(e) {
        const items = countryList.querySelectorAll('.country-item');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (filteredCountries.length > 0) {
                selectedIndex = Math.min(selectedIndex + 1, filteredCountries.length - 1);
                updateSelection();
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (filteredCountries.length > 0) {
                selectedIndex = Math.max(selectedIndex - 1, 0);
                updateSelection();
            }
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (selectedIndex >= 0 && selectedIndex < filteredCountries.length) {
                selectCountry(filteredCountries[selectedIndex]);
            }
        } else if (e.key === 'Escape') {
            countryDropdown.style.display = 'none';
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (countrySearch && !countrySearch.contains(e.target) && 
            countryDropdown && !countryDropdown.contains(e.target)) {
            countryDropdown.style.display = 'none';
        }
    });

    // Validate on blur
    countrySearch.addEventListener('blur', function() {
        setTimeout(() => {
            if (countrySearch.value && !countryCode.value) {
                countryError.style.display = 'block';
                countrySearch.classList.add('is-invalid');
            }
        }, 300); // Increased timeout to allow click events to fire
    });

    // Focus behavior
    countrySearch.addEventListener('focus', function() {
        if (this.value.length > 0) {
            const searchTerm = this.value.toLowerCase().trim();
            filteredCountries = countries.filter(country => 
                country.name.toLowerCase().includes(searchTerm)
            );
            if (filteredCountries.length > 0) {
                displayCountries(filteredCountries);
                countryDropdown.style.display = 'block';
            }
        }
    });

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
    
    // Live preview update
    function updatePreview() {
        const previewContact = document.getElementById('preview-contact');
        const previewAddress = document.getElementById('preview-address');
        
        if (!previewContact || !previewAddress) return;
        
        // Update contact preview
        const contactName = document.querySelector('input[name="contact_name"]')?.value || 'Contact Name';
        const contactPhone = document.querySelector('input[name="contact_phone"]')?.value || 'Phone Number';
        const company = document.querySelector('input[name="company"]')?.value;
        
        let contactPreview = contactName + '<br>' + contactPhone + '<br>';
        if (company) {
            contactPreview += company + '<br>';
        }
        previewContact.innerHTML = contactPreview;
        
        // Update address preview
        const addressLine1 = document.querySelector('input[name="address_line1"]')?.value || 'Address Line 1';
        const addressLine2 = document.querySelector('input[name="address_line2"]')?.value;
        const city = document.querySelector('input[name="city"]')?.value || 'City';
        const state = document.querySelector('input[name="state"]')?.value || 'State';
        const postalCode = document.querySelector('input[name="postal_code"]')?.value || 'Postal Code';
        const selectedCountry = countries.find(c => c.code === countryCode.value);
        
        let addressPreview = addressLine1 + '<br>';
        if (addressLine2) {
            addressPreview += addressLine2 + '<br>';
        }
        addressPreview += city + ', ' + state + ' ' + postalCode + '<br>' + 
                         (selectedCountry ? selectedCountry.name : 'Country');
        previewAddress.innerHTML = addressPreview;
    }
    
    // Attach event listeners to all form inputs
    const formInputs = document.querySelectorAll(
        'input[name="contact_name"], input[name="contact_phone"], input[name="company"], ' +
        'input[name="address_line1"], input[name="address_line2"], input[name="city"], ' +
        'input[name="state"], input[name="postal_code"]'
    );
    
    formInputs.forEach(input => {
        input.addEventListener('input', updatePreview);
    });
    
    // Validate form on submit
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!countryCode.value) {
                e.preventDefault();
                countrySearch.classList.add('is-invalid');
                countryError.style.display = 'block';
                countrySearch.focus();
                
                // Scroll to the country field
                countrySearch.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }
        });
    }
    
    // Initial preview update
    updatePreview();
});
</script>
@endsection