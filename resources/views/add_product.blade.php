@extends('layouts.layout')
@section('title', 'Add Product')

@section('content')
<style>
    /* Mobile (0 - 576px) */
    @media (max-width: 576px) {
        .container {
            max-width: 100%;
            padding: 0 15px;
        }

        h3 {
            font-size: 1.3rem;
            text-align: center;
            margin-bottom: 1rem;
        }

        form.card {
            padding: 15px;
            border: 1px solid #ddd;
            box-shadow: none;
        }

        .d-flex {
            flex-direction: column;
            gap: 10px;
        }

        .btn {
            width: 100%;
        }
    }

    /* Tablet (577px - 992px) */
    @media (min-width: 577px) and (max-width: 992px) {
        .container {
            max-width: 700px;
            padding: 0 20px;
        }

        h3 {
            font-size: 1.5rem;
            text-align: center;
        }

        form.card {
            padding: 20px;
        }

        .btn {
            font-size: 1rem;
        }
    }

    /* Desktop (993px and above) */
    @media (min-width: 993px) {
        .container {
            max-width: 600px;
            margin: auto;
        }

        form.card {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 25px;
        }

        h3 {
            font-size: 1.6rem;
        }
    }
</style>
<div class="container">
    <h3 class="text-primary fw-bold mb-4">Add Product</h3>

    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="card p-4 shadow-sm">
        @csrf

        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

       <div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Category</label>
        <select name="category" class="form-control" required>
    <option value="">Select Category</option>
    @foreach ($categories as $c)
        <option value="{{ $c->id }}">{{ $c->name }}</option>
    @endforeach
</select>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Sub Category</label>
        <select name="sub_category" class="form-control" required>
    <option value="">Select Sub Category</option>
    @foreach ($subcategories as $sc)
        <option value="{{ $sc->id }}">{{ $sc->name }}</option>
    @endforeach
</select>

    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Brand</label>
        <select name="brand" class="form-control" required>
    <option value="">Select Brand</option>
    @foreach ($brands as $b)
        <option value="{{ $b->id }}">{{ $b->name }}</option>
    @endforeach
</select>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">kg</label>
  <select name="size" class="form-control" required>
    <option value="">Select kg</option>
    @foreach ($sizes as $s)
        <option value="{{ $s->id }}">`{{ $s->name }}</option>
    @endforeach
</select>
    </div>
</div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">HSN Code</label>
                <input type="text" name="hsn_code" class="form-control">
            </div>

            <!-- <div class="col-md-4 mb-3">
                <label class="form-label">GST (%)</label>
                <input type="number" step="0.01" name="gst" class="form-control">
            </div> -->

            <!-- <div class="col-md-4 mb-3"> -->
            <input type="hidden" name="barcode" class="form-control">
            <!-- </div> -->
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Purchase Price (₹)</label>
                <input type="number" step="0.01" name="purchase_price" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Selling Price (₹)</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Minimum Stock</label>
                <input type="number" name="min_stock" class="form-control" value="5">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Product Image</label>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">← Back</a>
            <button type="submit" class="btn btn-primary">Save Product</button>
        </div>
    </form>
</div>
@endsection