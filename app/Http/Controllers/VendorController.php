<?php

namespace App\Http\Controllers\Purchase;
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;

class VendorController extends Controller
{
    public function index() {
        $vendors = Vendor::all();
        return view('purchases.vendors.index', compact('vendors'));
    }

    public function create() {
        return view('purchases.vendors.create');
    }

    public function store(Request $request) {

        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'gst_number' => 'nullable|string|max:50',
            'payment_type' => 'nullable|string|max:100',
        ]);

        Vendor::create([
            'name'         => $request->name,
            'company_name' => $request->company_name,
            'address'      => $request->address,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'gst_number'   => $request->gst_number,
            'payment_type' => $request->payment_type,
        ]);

        return redirect()->route('purchases.vendors.index')
                         ->with('success', 'Supplier added successfully');
    }

    public function edit(Vendor $vendor) {
        return view('purchases.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor) {

        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'gst_number' => 'nullable|string|max:50',
            'payment_type' => 'nullable|string|max:100',
        ]);

        $vendor->update([
            'name'         => $request->name,
            'company_name' => $request->company_name,
            'address'      => $request->address,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'gst_number'   => $request->gst_number,
            'payment_type' => $request->payment_type,
        ]);

        return redirect()->route('purchases.vendors.index')
                         ->with('success', 'Supplier updated successfully');
    }

    public function destroy(Vendor $vendor) {
        $vendor->delete();
        return redirect()->route('purchases.vendors.index')
                         ->with('success', 'Supplier deleted');
    }
}
