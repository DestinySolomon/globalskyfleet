<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



 

class AddressController extends Controller
{

    use AuthorizesRequests;
    /**
     * Display a listing of the user's addresses.
     */
    public function index()
    {
        $addresses = Auth::user()->addresses()
            ->orderBy('is_default', 'desc')
            ->orderBy('type')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('addresses.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new address.
     */
    public function create()
    {
        $addressTypes = [
            'shipping' => 'Shipping Address',
            'billing' => 'Billing Address',
            'home' => 'Home Address',
            'work' => 'Work Address',
        ];

        return view('addresses.create', compact('addressTypes'));
    }

    /**
     * Store a newly created address in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['shipping', 'billing', 'home', 'work'])],
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'company' => 'nullable|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country_code' => 'required|string|size:2',
            'is_default' => 'nullable|boolean',
        ]);

        // Add user_id to validated data
        $validated['user_id'] = Auth::id();

        // If marking as default, unset other defaults of same type
        if ($request->boolean('is_default')) {
            Auth::user()->addresses()
                ->where('type', $validated['type'])
                ->where('is_default', true)
                ->update(['is_default' => false]);
        } else {
            $validated['is_default'] = false;
        }

        // Create the address
        Address::create($validated);

        return redirect()->route('addresses.index')
            ->with('success', 'Address added successfully!');
    }

    /**
     * Display the specified address.
     */
    public function show(Address $address)
    {
        // Check authorization
        $this->authorize('view', $address);

        return view('addresses.show', compact('address'));
    }

    /**
     * Show the form for editing the specified address.
     */
    public function edit(Address $address)
    {
        // Check authorization
        $this->authorize('update', $address);

        $addressTypes = [
            'shipping' => 'Shipping Address',
            'billing' => 'Billing Address',
            'home' => 'Home Address',
            'work' => 'Work Address',
        ];

        return view('addresses.edit', compact('address', 'addressTypes'));
    }

    /**
     * Update the specified address in storage.
     */
    public function update(Request $request, Address $address)
    {
        // Check authorization
        $this->authorize('update', $address);

        $validated = $request->validate([
            'type' => ['required', Rule::in(['shipping', 'billing', 'home', 'work'])],
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'company' => 'nullable|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country_code' => 'required|string|size:2',
            'is_default' => 'nullable|boolean',
        ]);

        // If changing type and is_default is true, unset other defaults of new type
        if ($request->boolean('is_default') && $address->type !== $validated['type']) {
            Auth::user()->addresses()
                ->where('type', $validated['type'])
                ->where('is_default', true)
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        // If marking as default, unset other defaults of same type
        if ($request->boolean('is_default')) {
            Auth::user()->addresses()
                ->where('type', $validated['type'])
                ->where('is_default', true)
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $address->update($validated);

        return redirect()->route('addresses.index')
            ->with('success', 'Address updated successfully!');
    }

    /**
     * Remove the specified address from storage.
     */
    public function destroy(Address $address)
    {
        // Check authorization
        $this->authorize('delete', $address);

        // Check if address is being used in shipments
        $isUsed = $address->senderShipments()->exists() || 
                  $address->recipientShipments()->exists();

        if ($isUsed) {
            return redirect()->route('addresses.index')
                ->with('error', 'Cannot delete address. It is being used in shipments.');
        }

        // If deleting a default address, set another address as default
        if ($address->is_default) {
            $newDefault = Auth::user()->addresses()
                ->where('type', $address->type)
                ->where('id', '!=', $address->id)
                ->first();

            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        $address->delete();

        return redirect()->route('addresses.index')
            ->with('success', 'Address deleted successfully!');
    }

    /**
     * Set an address as default for its type.
     */
    public function setDefault(Address $address)
    {
        // Check authorization
        $this->authorize('update', $address);

        // Unset other defaults of same type
        Auth::user()->addresses()
            ->where('type', $address->type)
            ->where('is_default', true)
            ->update(['is_default' => false]);

        // Set this address as default
        $address->update(['is_default' => true]);

        return redirect()->route('addresses.index')
            ->with('success', 'Address set as default successfully!');
    }

    /**
     * Get addresses by type (AJAX endpoint).
     */
    public function getByType(Request $request)
    {
        $request->validate([
            'type' => ['required', Rule::in(['shipping', 'billing', 'home', 'work'])],
        ]);

        $addresses = Auth::user()->addresses()
            ->where('type', $request->type)
            ->orderBy('is_default', 'desc')
            ->orderBy('contact_name')
            ->get(['id', 'contact_name', 'address_line1', 'city', 'country_code', 'is_default']);

        return response()->json($addresses);
    }
}