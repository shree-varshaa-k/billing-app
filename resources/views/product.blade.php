@extends('layouts.layout')
@section('title', 'Products')

@section('content')
<style>
    /* 🔹 Default (Desktop first design) */
    .d-flex.justify-content-between.align-items-center.mb-4 {
        flex-wrap: wrap;
    }

    /* 🔹 Medium Devices (Tablets) */
    @media (max-width: 992px) {
        .container-fluid {
            padding: 10px;
        }

        .d-flex.justify-content-between.align-items-center.mb-4 {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .btn.btn-primary {
            align-self: flex-end;
        }

        table th,
        table td {
            font-size: 14px;
            padding: 6px;
        }

        .btn {
            font-size: 13px;
            padding: 4px 8px;
        }
    }

    /* 🔹 Small Devices (Mobile phones) */
    @media (max-width: 576px) {
        h3.text-primary {
            font-size: 20px;
        }

        .d-flex.justify-content-between.align-items-center.mb-4 {
            flex-direction: column;
            align-items: stretch;
        }

        .btn.btn-primary {
            width: 100%;
            /* Full width on mobile */
            margin-top: 8px;
        }

        .mb-3.d-flex.align-items-center.gap-3 {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }

        table {
            font-size: 13px;
        }

        table th:nth-child(3),
        table td:nth-child(3),
        table th:nth-child(4),
        table td:nth-child(4) {
            display: none;
            /* Hide price & stock on extra-small screens */
        }

        .btn-sm {
            font-size: 12px;
            padding: 3px 6px;
        }

        .badge {
            font-size: 12px;
            padding: 4px 6px;
        }
    }

    /* 🔹 Large Screens (Desktops wider than 1200px) */
    @media (min-width: 1200px) {
        .container-fluid {
            max-width: 1200px;
            margin: auto;
        }
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary fw-bold">Products</h3>
        <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">+ Add Product</a>
        <a href="{{ route('products.barcodes.download') }}" class="btn btn-sm btn-success">
            Download All Barcodes (PDF)
        </a>

    </div>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-primary text-center">
            <tr>
                <thead class="table-primary text-center">
                    <tr>
                        <th>SI.NO</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>HSN</th>
                        <th>Purchase Price</th>
                        <th>Sale Price</th>
                        <th>Stock</th>
                        <th>Barcode</th> <!-- 🔹 NEW -->
                        <th>Actions</th>
                    </tr>
                </thead>

            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)

            @php
            if ($product->stock < $product->min_stock) {
                $stockClass = 'bg-danger text-white';
                } elseif ($product->stock <= $product->min_stock + 10) {
                    $stockClass = 'bg-warning text-dark';
                    } else {
                    $stockClass = 'bg-success text-white';
                    }
                    @endphp

                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>

                        <td class="text-center">
                            @if($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}" width="40" height="40" class="rounded">
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td>{{ $product->name }}</td>
                        <td>{{ $product->brand }}</td>
                        <td>{{ $product->category }}</td>
                        <td>{{ $product->hsn_code ?? '--' }}</td>
                        <td>₹{{ number_format($product->purchase_price,2) }}</td>
                        <td>₹{{ number_format($product->price,2) }}</td>

                        <td class="text-center">
                            <span class="badge {{ $stockClass }} px-3 py-2 rounded-pill fw-bold">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <!-- BARCODE COLUMN -->
                        <td class="text-center">
                            @if($product->barcode)
                            <div style="display:flex; justify-content:center;">
                                <img src="{{ asset('storage/' . $product->barcode) }}"
                                    style="width:140px; height:auto; display:block;">
                            </div>
                            <div class="text-muted small mt-1">{{ $product->barcode_number }}</div>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-secondary">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted">No products found.</td>
                    </tr>
                    @endforelse
        </tbody>
    </table>
</div>
@endsection