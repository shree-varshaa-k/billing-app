@extends('layouts.layout')
@section('title', 'Edit Product')

@section('content')
<style>
/* Small screens (phones) */
@media (max-width: 576px) {
    .container {
        padding: 1rem;
    }
    form.card {
        padding: 1rem !important;
    }
    h3 {
        font-size: 1.3rem;
        text-align: center;
    }
    .btn {
        width: 100%;
    }
}

/* Medium screens (tablets) */
@media (min-width: 577px) and (max-width: 992px) {
    .container {
        max-width: 90%;
    }
    form.card {
        padding: 2rem;
    }
    h3 {
        font-size: 1.5rem;
    }
}

/* Large screens (desktops and above) */
@media (min-width: 993px) {
    .container {
        max-width: 700px;
    }
    h3 {
        font-size: 1.8rem;
    }
}
</style>
<div class="container">
    <h3 class="text-primary fw-bold mb-4">Edit Product</h3>

    <form method="POST" action="{{ route('products.update',$product->id) }}" enctype="multipart/form-data" class="card p-4 shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Category</label>
                <input type="text" name="category" class="form-control" value="{{ $product->category }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Sub Category</label>
                <input type="text" name="sub_category" class="form-control" value="{{ $product->sub_category }}">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Brand</label>
                <input type="text" name="brand" class="form-control" value="{{ $product->brand }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Size</label>
                <input type="text" name="size" class="form-control" value="{{ $product->size }}">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">HSN Code</label>
                <input type="text" name="hsn_code" class="form-control" value="{{ $product->hsn_code }}">
            </div>

            <!-- <div class="col-md-4 mb-3">
                <label class="form-label">GST (%)</label>
                <input type="number" step="0.01" name="gst" class="form-control" value="{{ $product->gst }}">
            </div> -->

            <div class="col-md-4 mb-3" style="display:none;">
    <label class="form-label">Barcode</label>
    <input type="text" class="form-control" value="{{ $product->barcode }}" disabled>
</div>

        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Purchase Price (₹)</label>
                <input type="number" step="0.01" name="purchase_price" class="form-control" value="{{ $product->purchase_price }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Selling Price (₹)</label>
                <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Minimum Stock</label>
                <input type="number" name="min_stock" class="form-control" value="{{ $product->min_stock }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Product Image</label>
            <input type="file" name="image" class="form-control">

            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" width="70" class="mt-2 rounded">
            @endif
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">← Back</a>
            <button class="btn btn-success">Update Product</button>
        </div>
    </form>
</div>
@endsection
