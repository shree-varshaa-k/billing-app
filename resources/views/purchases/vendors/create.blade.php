@extends('layouts.layout')

@section('title', 'Add Supplier')

@section('content')

<style>
    body {
        background: #eef2ff;
    }

    .gradient-header {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        padding: 22px;
        border-radius: 16px;
        margin-bottom: 25px;
        text-align: center;
        color: white;
        font-weight: 700;
        font-size: 1.7rem;
        letter-spacing: 0.5px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        animation: slideDown 0.7s ease;
    }

    @keyframes slideDown {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .form-card {
        background: #ffffff;
        padding: 35px 30px;
        border-radius: 20px;
        max-width: 760px;
        margin: auto;
        box-shadow: 0 8px 35px rgba(0,0,0,0.08);
        animation: fadeIn 0.8s ease-in-out;
        transition: 0.3s ease-in-out;
    }

    .form-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 45px rgba(0,0,0,0.14);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.97); }
        to { opacity: 1; transform: scale(1); }
    }

    .form-label {
        font-weight: 700;
        color: #374151;
    }

    input, textarea, select {
        border-radius: 12px !important;
        padding: 10px 14px !important;
        border: 1px solid #d1d5db !important;
        transition: 0.3s;
    }

    input:focus, textarea:focus, select:focus {
        border-color: #6285adff !important;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.25) !important;
    }

    .btn-save {
        padding: 12px 20px;
        font-size: 1.15rem;
        border-radius: 14px;
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        color: white;
        font-weight: 600;
        transition: 0.3s ease-in-out;
        box-shadow: 0px 6px 15px rgba(16,185,129,0.3);
    }

    .btn-save:hover {
        transform: scale(1.05);
        box-shadow: 0px 10px 25px rgba(16,185,129,0.4);
    }
</style>


<div class="container mt-4">

    <div class="gradient-header">
        Add Supplier Details
    </div>

    <div class="form-card">

        <form action="{{ route('purchases.vendors.store') }}" method="POST">
            @csrf

            <!-- Supplier Name -->
            <div class="mb-3">
                <label class="form-label">Supplier Name</label>
                <input type="text" name="name" class="form-control" required placeholder="Enter supplier full name">
            </div>

            <!-- Organization Name -->
            <div class="mb-3">
                <label class="form-label">Organization Name</label>
                <input type="text" name="company_name" class="form-control" placeholder="Enter organization/company name">
            </div>

            <!-- Address -->
            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="3" placeholder="Enter supplier address"></textarea>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="example@mail.com">
            </div>

            <!-- Phone -->
            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" placeholder="Enter phone number">
            </div>

            <!-- GST -->
            <div class="mb-3">
                <label class="form-label">GST / Tax ID</label>
                <input type="text" name="gst_number" class="form-control" placeholder="Enter GST / tax number">
            </div>

            <!-- Payment Type -->
            <div class="mb-3">
                <label class="form-label">Payment Type</label>
                <select name="payment_type" class="form-control" required>
                    <option value="">Select payment type</option>
                    <option value="Cash">Cash</option>
                    <option value="UPI">UPI</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>

            <button type="submit" class="btn btn-save w-100 mt-2">
                Save Supplier
            </button>
        </form>

    </div>
</div>

@endsection
