<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $contact_name
 * @property string $contact_phone
 * @property string|null $company
 * @property string $address_line1
 * @property string|null $address_line2
 * @property string $city
 * @property string $state
 * @property string $postal_code
 * @property string $country_code
 * @property bool $is_default
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shipment> $recipientShipments
 * @property-read int|null $recipient_shipments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shipment> $senderShipments
 * @property-read int|null $sender_shipments_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereUserId($value)
 */
	class Address extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $shipment_id
 * @property string|null $hs_code
 * @property string $purpose_of_export
 * @property string|null $invoice_number
 * @property bool $certificate_of_origin
 * @property bool $export_license_required
 * @property string|null $export_license_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomsItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Shipment $shipment
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsDeclaration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsDeclaration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsDeclaration query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsDeclaration whereCertificateOfOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsDeclaration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsDeclaration whereExportLicenseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsDeclaration whereExportLicenseRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsDeclaration whereHsCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsDeclaration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsDeclaration whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsDeclaration wherePurposeOfExport($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsDeclaration whereShipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsDeclaration whereUpdatedAt($value)
 */
	class CustomsDeclaration extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $customs_declaration_id
 * @property string $description
 * @property int $quantity
 * @property numeric $weight
 * @property numeric $value
 * @property string $currency
 * @property string|null $country_of_origin
 * @property string|null $hs_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CustomsDeclaration $customsDeclaration
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsItem whereCountryOfOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsItem whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsItem whereCustomsDeclarationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsItem whereHsCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsItem whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomsItem whereWeight($value)
 */
	class CustomsItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $shipment_id
 * @property int $user_id
 * @property numeric $amount
 * @property string $currency
 * @property string $status
 * @property string $payment_method
 * @property string|null $transaction_id
 * @property string|null $crypto_address
 * @property string|null $crypto_amount
 * @property string|null $transaction_hash
 * @property int $confirmations
 * @property string|null $crypto_status
 * @property array<array-key, mixed>|null $gateway_response
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $is_paid
 * @property-read mixed $is_pending
 * @property-read \App\Models\Shipment $shipment
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereConfirmations($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCryptoAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCryptoAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCryptoStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereGatewayResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereShipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTransactionHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUserId($value)
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property bool $is_international
 * @property numeric|null $max_weight
 * @property array<array-key, mixed>|null $max_dimensions
 * @property int|null $transit_time_min
 * @property int|null $transit_time_max
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shipment> $shipments
 * @property-read int|null $shipments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereIsInternational($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereMaxDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereMaxWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereTransitTimeMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereTransitTimeMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereUpdatedAt($value)
 */
	class Service extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $tracking_number
 * @property int $user_id
 * @property int $service_id
 * @property int $sender_address_id
 * @property int $recipient_address_id
 * @property string $status
 * @property string|null $current_location
 * @property numeric $weight
 * @property array<array-key, mixed>|null $dimensions
 * @property numeric $declared_value
 * @property string $currency
 * @property string $content_description
 * @property numeric $insurance_amount
 * @property bool $insurance_enabled
 * @property bool $requires_signature
 * @property bool $is_dangerous_goods
 * @property string|null $special_instructions
 * @property \Illuminate\Support\Carbon|null $estimated_delivery
 * @property \Illuminate\Support\Carbon|null $actual_delivery
 * @property \Illuminate\Support\Carbon|null $pickup_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CustomsDeclaration|null $customsDeclaration
 * @property-read mixed $is_delivered
 * @property-read mixed $is_in_transit
 * @property-read mixed $latest_status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\Address $recipientAddress
 * @property-read \App\Models\Address $senderAddress
 * @property-read \App\Models\Service $service
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShipmentStatusHistory> $statusHistory
 * @property-read int|null $status_history_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereActualDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereContentDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereCurrentLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereDeclaredValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereEstimatedDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereInsuranceAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereInsuranceEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereIsDangerousGoods($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment wherePickupDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereRecipientAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereRequiresSignature($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereSenderAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereSpecialInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereTrackingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipment whereWeight($value)
 */
	class Shipment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\Shipment|null $shipment
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStatusHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStatusHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStatusHistory query()
 */
	class ShipmentStatusHistory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Address> $addresses
 * @property-read int|null $addresses_count
 * @property-read mixed $full_name
 * @property-read mixed $initials
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shipment> $shipments
 * @property-read int|null $shipments_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

