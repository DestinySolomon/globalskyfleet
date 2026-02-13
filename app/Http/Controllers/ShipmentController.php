<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Address;
use App\Models\ShipmentStatusHistory;
use App\Models\Service;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Events\ShipmentStatusUpdated;
use App\Events\ShipmentCreated;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the user's shipments
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => ['nullable', 'string', Rule::in([
                'all', 'pending', 'confirmed', 'picked_up', 'in_transit', 
                'customs_hold', 'out_for_delivery', 'delivered', 'cancelled', 'returned'
            ])],
            'search' => ['nullable', 'string', 'max:100'],
            'sort' => ['nullable', 'string', Rule::in(['created_at', 'estimated_delivery', 'status'])],
            'order' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
        ]);

        if ($validator->fails()) {
            Log::warning('Invalid shipment list request', [
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
                'errors' => $validator->errors()->all()
            ]);
            
            return redirect()->route('shipments.index')->withErrors($validator);
        }

        $validated = $validator->validated();
        
        $query = Auth::user()->shipments()
            ->with(['senderAddress', 'recipientAddress', 'service'])
            ->select([
                'id', 'tracking_number', 'user_id', 'service_id', 
                'sender_address_id', 'recipient_address_id', 'status',
                'current_location', 'weight', 'declared_value', 'currency',
                'estimated_delivery', 'actual_delivery', 'pickup_date',
                'created_at', 'updated_at'
            ]);

        if (isset($validated['status']) && $validated['status'] !== 'all') {
            $query->where('status', $validated['status']);
        }

        if (isset($validated['search']) && !empty(trim($validated['search']))) {
            $searchTerm = '%' . trim($validated['search']) . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('tracking_number', 'LIKE', $searchTerm)
                  ->orWhereHas('senderAddress', function($q) use ($searchTerm) {
                      $q->where('city', 'LIKE', $searchTerm)
                        ->orWhere('country', 'LIKE', $searchTerm);
                  })
                  ->orWhereHas('recipientAddress', function($q) use ($searchTerm) {
                      $q->where('city', 'LIKE', $searchTerm)
                        ->orWhere('country', 'LIKE', $searchTerm);
                  });
            });
        }

        $sort = $validated['sort'] ?? 'created_at';
        $order = $validated['order'] ?? 'desc';
        $query->orderBy($sort, $order);

        $shipments = $query->paginate(20)->withQueryString();

        Log::channel('security')->info('User accessed shipment list', [
            'user_id' => Auth::id(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'filters' => $validated,
            'result_count' => $shipments->total()
        ]);

        return view('shipments.index', compact('shipments'));
    }

    public function create()
    {
        $user = Auth::user();
        
        // Get billing addresses for sender
        $senderAddresses = $user->addresses()
            ->where('type', 'billing')
            ->get(['id', 'contact_name', 'address_line1', 'city', 'state', 'country_code']);
        
        // Get shipping addresses for recipient
        $recipientAddresses = $user->addresses()
            ->where('type', 'shipping')
            ->get(['id', 'contact_name', 'address_line1', 'city', 'state', 'country_code']);
        
        // Update services to match your actual database services
        $services = [
            'express' => 'Express Delivery (2-5 days) - Fastest',
            'economy' => 'Economy Shipping (5-10 days) - Most Economical',
            'freight' => 'Freight Service (7-14 days) - Heavy Cargo',
            'documents' => 'Document Delivery (3-7 days) - Secure',
        ];
        
        return view('shipments.create', compact('senderAddresses', 'recipientAddresses', 'services'));
    }

    public function store(Request $request)
    {
        // First, let's debug what we're receiving
        Log::channel('production')->info('Shipment creation request', [
            'user_id' => Auth::id(),
            'service_type' => $request->service_type,
            'all_data' => $request->except(['_token', 'payment_proof'])
        ]);
        
        // Map form service_type to database service codes
        $serviceMap = [
            'express' => 'EXP',      // Express service
            'economy' => 'ECO',      // Economy service  
            'freight' => 'FRT',      // Freight service
            'documents' => 'DOC',    // Documents service
        ];
        
        // Get the service code based on form input
        $serviceCode = $serviceMap[$request->service_type] ?? 'ECO'; // Default to Economy
        
        Log::channel('production')->info('Service code mapped', [
            'requested_service' => $request->service_type,
            'mapped_code' => $serviceCode
        ]);
        
        // Find service by code
        $service = Service::where('code', $serviceCode)->first();
        
        Log::channel('production')->info('Service lookup result', [
            'service_code' => $serviceCode,
            'service_found' => $service ? 'yes' : 'no',
            'service_id' => $service ? $service->id : null
        ]);
        
        if (!$service) {
            Log::channel('production')->error('Service not found in database', [
                'requested_code' => $serviceCode,
                'available_services' => Service::pluck('code', 'id')->toArray()
            ]);
            return redirect()->back()
                ->with('error', 'Shipping service not available. Please contact support.')
                ->withInput();
        }
        
        // Check weight limits (both minimum and maximum)
        $weight = $request->weight;
        $minWeight = $service->min_weight ?? 0.1;
        $maxWeight = $service->max_weight;
        
        if ($weight < $minWeight) {
            Log::channel('production')->warning('Weight below service minimum', [
                'user_id' => Auth::id(),
                'requested_weight' => $weight,
                'service_code' => $service->code,
                'service_min_weight' => $minWeight
            ]);
            
            $errorMessage = "Package Too Light for {$service->name}\n\n" .
                "Your package weighs {$weight}kg, but {$service->name} requires a minimum of {$minWeight}kg.\n\n" .
                "Suggested solutions:\n" .
                "• Try a different service type\n" .
                "• Combine with other items to reach {$minWeight}kg\n" .
                "• Contact support for alternatives";
            
            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
        }
        
        if ($weight > $maxWeight) {
            Log::channel('production')->warning('Weight exceeds service limit', [
                'user_id' => Auth::id(),
                'requested_weight' => $weight,
                'service_code' => $service->code,
                'service_max_weight' => $maxWeight
            ]);
            
            $errorMessage = "Package Too Heavy for {$service->name}\n\n" .
                "Your package weighs {$weight}kg, but {$service->name} has a maximum limit of {$maxWeight}kg.\n\n" .
                "Suggested solutions:\n" .
                "• Use the Freight Service for heavy packages (up to 2000kg)\n" .
                "• Split into multiple shipments\n" .
                "• Contact support for specialized shipping options";
            
            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
        }
        
        $validator = Validator::make(array_merge($request->all(), [
            'service_id' => $service->id,
            'dimensions_unit' => 'cm',
            'currency' => 'USD'
        ]), [
            'service_id' => ['required', 'exists:services,id'],
            'sender_address_id' => [
                'required',
                Rule::exists('addresses', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                })
            ],
            'recipient_address_id' => [
                'required',
                'different:sender_address_id',
                Rule::exists('addresses', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                })
            ],
            'weight' => ['required', 'numeric', 'min:0.1', 'max:' . $service->max_weight],
            'dimensions_length' => ['nullable', 'numeric', 'min:1', 'max:500'],
            'dimensions_width' => ['nullable', 'numeric', 'min:1', 'max:500'],
            'dimensions_height' => ['nullable', 'numeric', 'min:1', 'max:500'],
            'dimensions_unit' => ['required', 'in:cm,in'],
            'declared_value' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'currency' => ['required', 'string', 'size:3'],
            'content_description' => ['required', 'string', 'max:500'],
            'insurance_enabled' => ['nullable', 'boolean'],
            'insurance_amount' => ['nullable', 'required_if:insurance_enabled,1', 'numeric', 'min:0', 'max:100000'],
            'requires_signature' => ['nullable', 'boolean'],
            'is_dangerous_goods' => ['nullable', 'boolean'],
            'special_instructions' => ['nullable', 'string', 'max:1000'],
            'pickup_date' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        if ($validator->fails()) {
            Log::channel('production')->warning('Shipment creation validation failed', [
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
                'errors' => $validator->errors()->all(),
                'error_details' => $validator->errors()->toArray(),
                'service_id' => $service->id,
                'service_code' => $service->code
            ]);
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        try {
            Log::channel('production')->info('Validation passed, attempting to create shipment', [
                'user_id' => Auth::id(),
                'service_id' => $service->id
            ]);

            $shipment = new Shipment();
            $shipment->user_id = Auth::id();
            $shipment->service_id = $service->id;
            $shipment->sender_address_id = $validated['sender_address_id'];
            $shipment->recipient_address_id = $validated['recipient_address_id'];
            $shipment->weight = $validated['weight'];
            
            // Handle dimensions
            if ($request->filled('dimensions_length') && $request->filled('dimensions_width') && $request->filled('dimensions_height')) {
                $shipment->dimensions = json_encode([
                    'length' => $validated['dimensions_length'],
                    'width' => $validated['dimensions_width'],
                    'height' => $validated['dimensions_height'],
                    'unit' => $validated['dimensions_unit']
                ]);
            }
            
            $shipment->declared_value = $validated['declared_value'];
            $shipment->currency = $validated['currency'];
            $shipment->content_description = strip_tags($validated['content_description']);
            $shipment->insurance_enabled = $request->boolean('insurance_enabled');
            $shipment->insurance_amount = $shipment->insurance_enabled ? ($validated['insurance_amount'] ?? $validated['declared_value']) : 0;
            $shipment->requires_signature = $request->boolean('requires_signature');
            $shipment->is_dangerous_goods = $request->boolean('is_dangerous_goods');
            $shipment->special_instructions = $request->filled('special_instructions') 
                ? strip_tags($validated['special_instructions']) 
                : null;
            $shipment->pickup_date = $validated['pickup_date'] ?? null;
            $shipment->status = 'pending';
            
            // Set estimated delivery based on service transit times
            if ($service->transit_time_min && $service->transit_time_max) {
                $avgTransitDays = ceil(($service->transit_time_min + $service->transit_time_max) / 2);
                $shipment->estimated_delivery = now()->addDays($avgTransitDays);
            }
            
            $shipment->save();

            Log::channel('production')->info('Shipment saved to database', [
                'shipment_id' => $shipment->id,
                'tracking_number' => $shipment->tracking_number,
                'user_id' => Auth::id(),
            ]);

            // Create status history
            $shipment->statusHistory()->create([
                'status' => 'pending',
                'location' => 'System',
                'description' => 'Shipment created',
                'scan_datetime' => now(),
            ]);

            // Create invoice
            $invoice = $this->createInvoiceForShipment($shipment, $service);

            // Trigger events
            event(new ShipmentCreated($shipment));
            event(new ShipmentStatusUpdated($shipment, '', 'pending'));

            Log::channel('security')->info('Shipment created successfully', [
                'user_id' => Auth::id(),
                'shipment_id' => $shipment->id,
                'tracking_number' => $shipment->tracking_number,
                'invoice_id' => $invoice->id,
                'service' => $service->name,
                'ip' => $request->ip()
            ]);

            // Redirect to payment page
           return redirect()->route('billing.pay', ['invoice' => $invoice->id])
                ->with('success', 'Shipment created successfully! Your tracking number is: ' . $shipment->tracking_number . '. Please pay the invoice to proceed.');

        } catch (\Exception $e) {
            Log::error('Shipment creation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to create shipment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Create invoice for a shipment
     * GLOBAL MINIMUM: $500 for all services
     */
    private function createInvoiceForShipment(Shipment $shipment, Service $service)
    {
        // Calculate price based on service type
        $weight = $shipment->weight;
        $basePrice = 0;
        
        // Pricing based on your service types
        // Note: Individual minimums are still used for calculation, 
        // but a global $500 minimum is enforced at the end
        switch($service->code) {
            case 'EXP': // Express
                $ratePerKg = 25;  // $25 per kg for Express
                $basePrice = $weight * $ratePerKg;
                $minimum = 500;   // Minimum $500 for Express
                break;
                
            case 'ECO': // Economy
                $ratePerKg = 12;  // $12 per kg for Economy
                $basePrice = $weight * $ratePerKg;
                $minimum = 500;   // Minimum $500 for Economy
                break;
                
            case 'FRT': // Freight
                $ratePerKg = 8;   // $8 per kg for Freight
                $basePrice = $weight * $ratePerKg;
                $minimum = 500;   // Minimum $500 for Freight
                break;
                
            case 'DOC': // Documents
                $basePrice = 15;  // Flat $15 for documents (before minimum)
                $minimum = 500;   // Minimum $500 for Documents
                break;
                
            default:
                $ratePerKg = 15;
                $basePrice = $weight * $ratePerKg;
                $minimum = 500;   // Minimum $500 for any other service
        }
        
        // Apply service minimum charge
        if ($basePrice < $minimum) {
            $basePrice = $minimum;
        }
        
        // Add insurance if enabled
        $insuranceFee = 0;
        if ($shipment->insurance_enabled && $shipment->insurance_amount > 0) {
            $insuranceFee = $shipment->insurance_amount * 0.03; // 3% insurance fee
            $basePrice += $insuranceFee;
        }
        
        // Add signature fee
        $signatureFee = 0;
        if ($shipment->requires_signature) {
            $signatureFee = 20;
            $basePrice += $signatureFee;
        }
        
        // Add dangerous goods fee
        $dangerousGoodsFee = 0;
        if ($shipment->is_dangerous_goods) {
            $dangerousGoodsFee = 75;
            $basePrice += $dangerousGoodsFee;
        }
        
        // Generate invoice number
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        
        // Create invoice items array
        $items = [];
        
        // Main shipping service item
        $items[] = [
            'description' => $service->name . ' shipping for ' . $weight . 'kg',
            'quantity' => 1,
            'unit_price' => round($basePrice - $insuranceFee - $signatureFee - $dangerousGoodsFee, 2),
            'amount' => round($basePrice - $insuranceFee - $signatureFee - $dangerousGoodsFee, 2)
        ];
        
        // Add insurance item if applicable
        if ($insuranceFee > 0) {
            $items[] = [
                'description' => 'Insurance coverage ($' . number_format($shipment->insurance_amount, 2) . ')',
                'quantity' => 1,
                'unit_price' => round($insuranceFee, 2),
                'amount' => round($insuranceFee, 2)
            ];
        }
        
        // Add signature service if applicable
        if ($signatureFee > 0) {
            $items[] = [
                'description' => 'Signature on delivery',
                'quantity' => 1,
                'unit_price' => round($signatureFee, 2),
                'amount' => round($signatureFee, 2)
            ];
        }
        
        // Add dangerous goods handling if applicable
        if ($dangerousGoodsFee > 0) {
            $items[] = [
                'description' => 'Dangerous goods handling fee',
                'quantity' => 1,
                'unit_price' => round($dangerousGoodsFee, 2),
                'amount' => round($dangerousGoodsFee, 2)
            ];
        }
        
        // Calculate total before global minimum
        $totalAmount = array_sum(array_column($items, 'amount'));
        
        // ENFORCE GLOBAL MINIMUM OF $500
        // This ensures NO invoice is less than $500, regardless of service
        $globalMinimum = 500;
        if ($totalAmount < $globalMinimum) {
            // Add a minimum charge adjustment line item
            $adjustmentAmount = $globalMinimum - $totalAmount;
            $items[] = [
                'description' => 'Minimum service charge',
                'quantity' => 1,
                'unit_price' => round($adjustmentAmount, 2),
                'amount' => round($adjustmentAmount, 2)
            ];
            $totalAmount = $globalMinimum;
        }
        
        // Create invoice
        $invoice = Invoice::create([
            'user_id' => $shipment->user_id,
            'invoice_number' => $invoiceNumber,
            'amount' => round($totalAmount, 2),
            'currency' => $shipment->currency,
            'description' => 'Shipping Service - ' . $service->name . ' - Tracking #' . $shipment->tracking_number,
            'invoice_date' => now(),
            'due_date' => now()->addDays(7),
            'status' => 'pending',
            'items' => json_encode($items),
        ]);
        
        // Link shipment to invoice
        $shipment->invoice_id = $invoice->id;
        $shipment->save();
        
        return $invoice;
    }

    public function show(Request $request, Shipment $shipment)
    {
        if ($shipment->user_id != Auth::id()) {
            Log::warning('Unauthorized shipment access attempt', [
                'user_id' => Auth::id(),
                'attempted_shipment_id' => $shipment->id,
                'shipment_owner' => $shipment->user_id,
                'ip' => $request->ip()
            ]);
            
            abort(403, 'Unauthorized access to shipment.');
        }

        $shipment->load([
            'senderAddress:id,contact_name,contact_phone,company,address_line1,address_line2,city,state,postal_code,country_code',
            'recipientAddress:id,contact_name,contact_phone,company,address_line1,address_line2,city,state,postal_code,country_code',
            'service:id,name,description,transit_time_min,transit_time_max',
            'statusHistory' => function($query) {
                $query->select(['id', 'shipment_id', 'status', 'location', 'description', 'scan_datetime'])
                      ->orderBy('scan_datetime', 'desc');
            }
        ]);

        Log::channel('security')->info('Shipment viewed', [
            'user_id' => Auth::id(),
            'shipment_id' => $shipment->id,
            'tracking_number' => $shipment->tracking_number,
            'ip' => $request->ip()
        ]);

        return view('shipments.show', compact('shipment'));
    }

    public function edit(Request $request, Shipment $shipment)
    {
        if ($shipment->user_id != Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        if (!in_array($shipment->status, ['pending', 'confirmed'])) {
            return redirect()->route('shipments.show', $shipment)
                ->with('error', 'Shipment cannot be edited after processing has started.');
        }

        $addresses = Auth::user()->addresses()
            ->whereIn('type', ['shipping', 'both'])
            ->get(['id', 'name', 'type', 'address_line1', 'city', 'state', 'country', 'postal_code']);
        
        $services = Service::where('is_active', true)->get(['id', 'name', 'description', 'estimated_days', 'base_price']);

        return view('shipments.edit', compact('shipment', 'addresses', 'services'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        if ($shipment->user_id != Auth::id()) {
            Log::warning('Unauthorized shipment update attempt', [
                'user_id' => Auth::id(),
                'shipment_id' => $shipment->id,
                'ip' => $request->ip()
            ]);
            abort(403);
        }

        if (!in_array($shipment->status, ['pending', 'confirmed'])) {
            return redirect()->route('shipments.show', $shipment)
                ->with('error', 'Shipment cannot be updated after processing has started.');
        }

        $validator = Validator::make($request->all(), [
            'service_id' => ['required', 'exists:services,id'],
            'sender_address_id' => [
                'required',
                Rule::exists('addresses', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                })
            ],
            'recipient_address_id' => [
                'required',
                'different:sender_address_id',
                Rule::exists('addresses', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                })
            ],
            'weight' => ['required', 'numeric', 'min:0.1', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        try {
            $shipment->update([
                'service_id' => $validated['service_id'],
                'sender_address_id' => $validated['sender_address_id'],
                'recipient_address_id' => $validated['recipient_address_id'],
                'weight' => $validated['weight'],
            ]);

            $shipment->statusHistory()->create([
                'status' => $shipment->status,
                'location' => 'System',
                'description' => 'Shipment details updated by user',
                'scan_datetime' => now(),
            ]);

            Log::channel('security')->info('Shipment updated', [
                'user_id' => Auth::id(),
                'shipment_id' => $shipment->id,
                'ip' => $request->ip()
            ]);

            return redirect()->route('shipments.show', $shipment)
                ->with('success', 'Shipment updated successfully.');

        } catch (\Exception $e) {
            Log::error('Shipment update failed', [
                'user_id' => Auth::id(),
                'shipment_id' => $shipment->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to update shipment. Please try again.')
                ->withInput();
        }
    }

    public function cancel(Request $request, Shipment $shipment)
    {
        if ($shipment->user_id != Auth::id()) {
            abort(403);
        }

        if (!in_array($shipment->status, ['pending', 'confirmed'])) {
            return redirect()->route('shipments.show', $shipment)
                ->with('error', 'Shipment cannot be cancelled after processing has started.');
        }

        $validator = Validator::make($request->all(), [
            'cancellation_reason' => ['required', 'string', 'min:10', 'max:500']
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Please provide a valid cancellation reason (10-500 characters).');
        }

        try {
            // Store old status before changing
            $oldStatus = $shipment->status;
            
            $shipment->status = 'cancelled';
            $shipment->save();

            $shipment->statusHistory()->create([
                'status' => 'cancelled',
                'location' => 'System',
                'description' => 'Shipment cancelled by user. Reason: ' . strip_tags($request->cancellation_reason),
                'scan_datetime' => now(),
            ]);

            // 🔥 TRIGGER EVENT FOR STATUS CHANGE NOTIFICATION
            event(new ShipmentStatusUpdated($shipment, $oldStatus, 'cancelled'));

            Log::channel('security')->info('Shipment cancelled', [
                'user_id' => Auth::id(),
                'shipment_id' => $shipment->id,
                'reason' => $request->cancellation_reason,
                'ip' => $request->ip()
            ]);

            return redirect()->route('shipments.show', $shipment)
                ->with('success', 'Shipment cancelled successfully.');

        } catch (\Exception $e) {
            Log::error('Shipment cancellation failed', [
                'user_id' => Auth::id(),
                'shipment_id' => $shipment->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to cancel shipment. Please try again.');
        }
    }

    public function destroy(Request $request, Shipment $shipment)
    {
        if ($shipment->user_id != Auth::id()) {
            Log::warning('Unauthorized shipment deletion attempt', [
                'user_id' => Auth::id(),
                'shipment_id' => $shipment->id,
                'ip' => $request->ip()
            ]);
            abort(403);
        }

        if ($shipment->status !== 'pending') {
            return redirect()->route('shipments.show', $shipment)
                ->with('error', 'Only pending shipments can be deleted.');
        }

        try {
            Log::channel('security')->warning('Shipment deleted', [
                'user_id' => Auth::id(),
                'shipment_id' => $shipment->id,
                'tracking_number' => $shipment->tracking_number,
                'status' => $shipment->status,
                'ip' => $request->ip()
            ]);

            $shipment->delete();

            return redirect()->route('shipments.index')
                ->with('success', 'Shipment deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Shipment deletion failed', [
                'user_id' => Auth::id(),
                'shipment_id' => $shipment->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to delete shipment. Please try again.');
        }
    }

    public function track(Request $request)
    {
        if ($request->isMethod('get') && !$request->has('tracking_number')) {
            return view('pages.tracking');
        }

        $validator = Validator::make($request->all(), [
            'tracking_number' => ['required', 'string', 'regex:/^GS[A-Z0-9]{8}$/']
        ]);

        if ($validator->fails()) {
            Log::channel('production')->warning('Invalid tracking number format', [
                'tracking_number' => $request->tracking_number,
                'ip' => $request->ip()
            ]);
            
            return back()
                ->with('error', 'Invalid tracking number format. Please use format: GS followed by 8 letters/numbers.');
        }

        $trackingNumber = strtoupper(trim($request->tracking_number));
        
        $shipment = Shipment::where('tracking_number', $trackingNumber)
            ->with(['senderAddress', 'recipientAddress', 'service', 'statusHistory'])
            ->first();

        if (!$shipment) {
            Log::channel('production')->warning('Tracking number not found', [
                'tracking_number' => $trackingNumber,
                'ip' => $request->ip()
            ]);
            
            return back()
                ->with('error', 'Tracking number not found. Please check and try again.');
        }

        Log::channel('production')->info('Public tracking accessed', [
            'tracking_number' => $trackingNumber,
            'status' => $shipment->status,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return view('pages.tracking', compact('shipment'));
    }

    public function getStats()
    {
        $userId = Auth::id();
        
        $stats = cache()->remember("user_{$userId}_shipment_stats", 300, function() use ($userId) {
            return [
                'total' => Shipment::where('user_id', $userId)->count(),
                'pending' => Shipment::where('user_id', $userId)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->count(),
                'in_transit' => Shipment::where('user_id', $userId)
                    ->whereIn('status', ['picked_up', 'in_transit', 'customs_hold', 'out_for_delivery'])
                    ->count(),
                'delivered' => Shipment::where('user_id', $userId)
                    ->where('status', 'delivered')
                    ->count(),
                'cancelled' => Shipment::where('user_id', $userId)
                    ->where('status', 'cancelled')
                    ->count(),
            ];
        });

        return $stats;
    }

    public function dashboardTracking(Request $request)
    {
        $shipment = null;
        
        if ($request->has('tracking_number')) {
            $trackingNumber = strtoupper(trim($request->tracking_number));
            
            if (!preg_match('/^GS[A-Z0-9]{8}$/', $trackingNumber)) {
                return redirect()->route('dashboard.tracking')
                    ->with('error', 'Invalid tracking number format. Use format: GS followed by 8 letters/numbers.')
                    ->withInput();
            }
            
            $shipment = Auth::user()->shipments()
                ->where('tracking_number', $trackingNumber)
                ->first();
            
            if (!$shipment) {
                Log::channel('security')->warning('User attempted to track non-owned shipment', [
                    'user_id' => Auth::id(),
                    'tracking_number' => $trackingNumber,
                    'ip' => $request->ip()
                ]);
            }
        }
        
        return view('tracking.index', compact('shipment'));
    }
}