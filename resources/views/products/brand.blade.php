@extends('layouts.layout')
@section('title', 'Brand')

@section('content')
<div class="container">

    <h3 class="mb-3">Brand</h3>

    <!-- Add Brand Button -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addBrandModal">
        Add Brand
    </button>

    <!-- Brands Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Brand</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brands as $b)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $b->name }}</td>
                <td class="d-flex gap-2">

                    <!-- Edit Button -->
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editBrandModal{{ $b->id }}">
                        Edit
                    </button>

                    <!-- Delete Button -->
                    <form method="POST" action="{{ route('products.brands.delete', $b->id) }}" onsubmit="return confirm('Are you sure you want to delete this brand?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>

            <!-- Edit Brand Modal -->
            <div class="modal fade" id="editBrandModal{{ $b->id }}" tabindex="-1" aria-labelledby="editBrandModalLabel{{ $b->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('products.brands.update', $b->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title" id="editBrandModalLabel{{ $b->id }}">Edit Brand</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="brandName{{ $b->id }}" class="form-label">Brand Name</label>
                                    <input type="text" name="name" id="brandName{{ $b->id }}" class="form-control" value="{{ $b->name }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update Brand</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Brand Modal -->
<div class="modal fade" id="addBrandModal" tabindex="-1" aria-labelledby="addBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('products.brands.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addBrandModalLabel">Add Brand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="brandName" class="form-label">Brand Name</label>
                        <input type="text" name="name" id="brandName" class="form-control" placeholder="Enter Brand Name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Brand</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
