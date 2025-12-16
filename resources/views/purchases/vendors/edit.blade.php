@extends('layouts.layout')

@section('title', 'Edit Supplier')

@section('content')
<style>
    .form-card {
        background: #ffffff;
        padding: 30px;
        border-radius: 18px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        max-width: 800px;
        margin: 24px auto;
        transition: 0.3s ease;
    }

    .form-title {
        font-size: 1.5rem;
        color: #0b5ed7;
        font-weight: 700;
        margin-bottom: 18px;
        text-align: left;
    }

    .form-label {
        font-weight: 600;
        color: #333;
    }

    .btn-save {
        padding: 10px 20px;
        font-size: 1rem;
        border-radius: 10px;
    }

    input, select, textarea {
        border-radius: 8px !important;
    }
</style>

<div class="container">
    <div class="form-card">
        <h4 class="form-title">Edit Supplier Details</h4>

        <!-- show validation errors -->
        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('purchases.vendors.update', $vendor->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Supplier Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Supplier Name <span class="text-danger">*</span></label>
                <input id="name" type="text" name="name" class="form-control"
                       value="{{ old('name', $vendor->name) }}" required placeholder="Enter supplier full name">
            </div>

            <!-- Organization Name -->
            <div class="mb-3">
                <label for="company_name" class="form-label">Organization Name</label>
                <input id="company_name" type="text" name="company_name" class="form-control"
                       value="{{ old('company_name', $vendor->company_name) }}" placeholder="Organization / Company">
            </div>

            <!-- Address -->
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea id="address" name="address" class="form-control" rows="3"
                          placeholder="Enter supplier address">{{ old('address', $vendor->address) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" class="form-control"
                           value="{{ old('email', $vendor->email) }}" placeholder="example@mail.com">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input id="phone" type="text" name="phone" class="form-control"
                           value="{{ old('phone', $vendor->phone) }}" placeholder="Phone number">
                </div>
            </div>

            <!-- GST / TAX -->
            <div class="mb-3">
                <label for="gst_number" class="form-label">GST / TAX Number</label>
                <input id="gst_number" type="text" name="gst_number" class="form-control"
                       value="{{ old('gst_number', $vendor->gst_number) }}" placeholder="GST / Tax ID">
            </div>

            <!-- Payment Type -->
            <div class="mb-3">
                <label for="payment_type" class="form-label">Payment Type</label>
                <select id="payment_type" name="payment_type" class="form-select">
                    @php $pt = old('payment_type', $vendor->payment_type); @endphp
                    <option value="">Select payment type</option>
                    <option value="Cash"       {{ $pt === 'Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="UPI"        {{ $pt === 'UPI' ? 'selected' : '' }}>UPI</option>
                    <option value="Credit Card"{{ $pt === 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="Debit Card" {{ $pt === 'Debit Card' ? 'selected' : '' }}>Debit Card</option>
                    <option value="Bank Transfer" {{ $pt === 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="Other"      {{ $pt === 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('purchases.vendors.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success btn-save">Update Supplier</button>
            </div>
        </form>
    </div>
</div>

@endsection
