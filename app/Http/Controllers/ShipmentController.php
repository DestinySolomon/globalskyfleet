<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Address;
use App\Models\ShipmentStatusHistory;
use App\Models\Service;
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
        $addresses = Auth::user()->addresses()
            ->where('type', 'shipping')
            ->orWhere('type', 'both')
            ->get();
        
        $services = [
            'express' => 'Express Delivery (1-3 days)',
            'economy' => 'Economy Shipping (5-7 days)',
            'standard' => 'Standard (3-5 days)',
        ];
        
        return view('shipments.create', compact('addresses', 'services'));
    }

    public function store(Request $request)
    {
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
            'dimensions_length' => ['required', 'numeric', 'min:1', 'max:500'],
            'dimensions_width' => ['required', 'numeric', 'min:1', 'max:500'],
            'dimensions_height' => ['required', 'numeric', 'min:1', 'max:500'],
            'dimensions_unit' => ['required', 'in:cm,in'],
            'declared_value' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'currency' => ['required', 'string', 'size:3'],
            'content_description' => ['required', 'string', 'max:500'],
            'insurance_enabled' => ['boolean'],
            'insurance_amount' => ['required_if:insurance_enabled,1', 'numeric', 'min:0', 'max:100000'],
            'requires_signature' => ['boolean'],
            'is_dangerous_goods' => ['boolean'],
            'special_instructions' => ['nullable', 'string', 'max:1000'],
            'pickup_date' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        if ($validator->fails()) {
            Log::warning('Shipment creation validation failed', [
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
                'errors' => $validator->errors()->all()
            ]);
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        try {
            $shipment = new Shipment();
            $shipment->user_id = Auth::id();
            $shipment->service_id = $validated['service_id'];
            $shipment->sender_address_id = $validated['sender_address_id'];
            $shipment->recipient_address_id = $validated['recipient_address_id'];
            $shipment->weight = $validated['weight'];
            $shipment->dimensions = json_encode([
                'length' => $validated['dimensions_length'],
                'width' => $validated['dimensions_width'],
                'height' => $validated['dimensions_height'],
                'unit' => $validated['dimensions_unit']
            ]);
            $shipment->declared_value = $validated['declared_value'];
            $shipment->currency = $validated['currency'];
            $shipment->content_description = strip_tags($validated['content_description']);
            $shipment->insurance_enabled = $validated['insurance_enabled'] ?? false;
            $shipment->insurance_amount = $validated['insurance_amount'] ?? 0;
            $shipment->requires_signature = $validated['requires_signature'] ?? false;
            $shipment->is_dangerous_goods = $validated['is_dangerous_goods'] ?? false;
            $shipment->special_instructions = isset($validated['special_instructions']) 
                ? strip_tags($validated['special_instructions']) 
                : null;
            $shipment->pickup_date = $validated['pickup_date'] ?? null;
            $shipment->status = 'pending';
            
            $service = Service::find($validated['service_id']);
            if ($service && $service->estimated_days) {
                $shipment->estimated_delivery = now()->addDays($service->estimated_days);
            }
            
            $shipment->save();

            // Create initial status history
            $shipment->statusHistory()->create([
                'status' => 'pending',
                'location' => 'System',
                'description' => 'Shipment created',
                'scan_datetime' => now(),
            ]);

            // 🔥 TRIGGER EVENTS FOR NOTIFICATIONS
            // Trigger ShipmentCreated event
            event(new ShipmentCreated($shipment));
            
            // Trigger ShipmentStatusUpdated event for initial status
            event(new ShipmentStatusUpdated($shipment, '', 'pending'));

            Log::channel('security')->info('Shipment created successfully', [
                'user_id' => Auth::id(),
                'shipment_id' => $shipment->id,
                'tracking_number' => $shipment->tracking_number,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->route('shipments.show', $shipment)
                ->with('success', 'Shipment created successfully! Your tracking number is: ' . $shipment->tracking_number);

        } catch (\Exception $e) {
            Log::error('Shipment creation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to create shipment. Please try again.')
                ->withInput();
        }
    }

    public function show(Request $request, Shipment $shipment)
    {
        if ($shipment->user_id !== Auth::id()) {
            Log::warning('Unauthorized shipment access attempt', [
                'user_id' => Auth::id(),
                'attempted_shipment_id' => $shipment->id,
                'shipment_owner' => $shipment->user_id,
                'ip' => $request->ip()
            ]);
            
            abort(403, 'Unauthorized access to shipment.');
        }

        $shipment->load([
            'senderAddress:id,name,address_line1,address_line2,city,state,country,postal_code,phone',
            'recipientAddress:id,name,address_line1,address_line2,city,state,country,postal_code,phone',
            'service:id,name,description,estimated_days',
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
        if ($shipment->user_id !== Auth::id()) {
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
        if ($shipment->user_id !== Auth::id()) {
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
        if ($shipment->user_id !== Auth::id()) {
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
        if ($shipment->user_id !== Auth::id()) {
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
            return view('tracking.index');
        }

        $validator = Validator::make($request->all(), [
            'tracking_number' => ['required', 'string', 'regex:/^GS[A-Z0-9]{8}$/']
        ]);

        if ($validator->fails()) {
            Log::channel('tracking')->warning('Invalid tracking number format', [
                'tracking_number' => $request->tracking_number,
                'ip' => $request->ip()
            ]);
            
            return back()
                ->with('error', 'Invalid tracking number format. Please use format: GS followed by 8 letters/numbers.');
        }

        $trackingNumber = strtoupper(trim($request->tracking_number));
        
        $shipment = Shipment::where('tracking_number', $trackingNumber)->first();

        if (!$shipment) {
            Log::channel('tracking')->warning('Tracking number not found', [
                'tracking_number' => $trackingNumber,
                'ip' => $request->ip()
            ]);
            
            return back()
                ->with('error', 'Tracking number not found. Please check and try again.');
        }

        $trackingData = [
            'tracking_number' => $shipment->tracking_number,
            'status' => $shipment->status,
            'current_location' => $shipment->current_location,
            'estimated_delivery' => $shipment->estimated_delivery,
            'last_updated' => $shipment->updated_at,
            'history' => $shipment->statusHistory()
                ->select(['status', 'location', 'description', 'scan_datetime'])
                ->orderBy('scan_datetime', 'desc')
                ->get()
        ];

        Log::channel('tracking')->info('Public tracking accessed', [
            'tracking_number' => $trackingNumber,
            'status' => $shipment->status,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return view('tracking.public', compact('trackingData'));
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