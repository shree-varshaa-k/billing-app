@extends('layouts.layout')

@section('title', 'Supplier Details')

@section('content')

<style>
    /* Card container */
    .table-card {
        background: #ffffff;
        padding: 22px;
        border-radius: 18px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.10);
        transition: 0.3s ease;
    }

    .table-card:hover {
        box-shadow: 0 10px 28px rgba(0,0,0,0.16);
    }

    /* Table styling */
    table th {
        background: #e9f1ff !important;
        font-weight: 700;
        color: #0b5ed7;
    }

    table td {
        vertical-align: middle;
        font-size: 0.95rem;
        font-weight: 500;
    }

    /* Buttons */
    .btn-add {
        border-radius: 12px;
        padding: 8px 18px;
        font-weight: 600;
    }

    .btn-action {
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 0.85rem;
        transition: 0.25s;
    }

    .btn-action:hover {
        transform: scale(1.12);
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    /* Heading */
    .page-title {
        font-size: 1.7rem;
        font-weight: 700;
        color: #0b5ed7;
    }
</style>

<div class="container mt-3">

    <!-- Title + Add Button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="page-title">Supplier Details</h4>

        <a href="{{ route('purchases.vendors.create') }}" class="btn btn-primary btn-add">
            <i class="bi bi-plus-circle"></i> Add Supplier
        </a>
    </div>

    <div class="table-card">

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>SI.NO</th>
                    <th>Supplier Name</th>
                    <th>Organization Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>GST / TAX No.</th>
                    <th>Payment Type</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($vendors as $vendor)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $vendor->name }}</td>
                    <td>{{ $vendor->company_name }}</td>
                    <td>{{ $vendor->address }}</td>
                    <td>{{ $vendor->phone }}</td>
                    <td>{{ $vendor->gst_number }}</td>
                    <td>{{ $vendor->payment_type }}</td>


                    <td>
                        

                        <!-- Edit -->
                        <a href="{{ route('purchases.vendors.edit', $vendor->id) }}" 
                           class="btn btn-warning btn-sm btn-action">
                            <i class="bi bi-pencil-fill"></i>
                        </a>

                        <!-- Delete -->
                        <form action="{{ route('purchases.vendors.destroy', $vendor->id) }}" method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')

                            <button type="submit" 
                                    class="btn btn-danger btn-sm btn-action"
                                    onclick="return confirm('Delete this supplier?');">
                                <i class="bi bi-trash-fill"></i>
                            </button>

                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>

    </div>

</div>

@endsection
