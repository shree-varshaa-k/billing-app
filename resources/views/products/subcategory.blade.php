@extends('layouts.layout')
@section('title', 'Sub Category')

@section('content')
<div class="container">

    <h3 class="mb-3">Sub Category</h3>

    <!-- Add Sub Category Button -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addSubCategoryModal">
        Add Sub Category
    </button>

    <!-- Sub Categories Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subCategories as $sc)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $sc->category->name }}</td>
                <td>{{ $sc->name }}</td>
                <td class="d-flex gap-2">

                    <!-- Edit Button -->
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSubCategoryModal{{ $sc->id }}">
                        Edit
                    </button>

                    <!-- Delete Button -->
                    <form method="POST" action="{{ route('products.subcategories.delete', $sc->id) }}" onsubmit="return confirm('Are you sure you want to delete this subcategory?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>

            <!-- Edit Sub Category Modal -->
            <div class="modal fade" id="editSubCategoryModal{{ $sc->id }}" tabindex="-1" aria-labelledby="editSubCategoryModalLabel{{ $sc->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('products.subcategories.update', $sc->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title" id="editSubCategoryModalLabel{{ $sc->id }}">Edit Sub Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="category_id{{ $sc->id }}" class="form-label">Category</label>
                                    <select name="category_id" id="category_id{{ $sc->id }}" class="form-control" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ $sc->category_id == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="subCategoryName{{ $sc->id }}" class="form-label">Sub Category</label>
                                    <input type="text" name="name" id="subCategoryName{{ $sc->id }}" class="form-control" value="{{ $sc->name }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update Sub Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Sub Category Modal -->
<div class="modal fade" id="addSubCategoryModal" tabindex="-1" aria-labelledby="addSubCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('products.subcategories.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubCategoryModalLabel">Add Sub Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="subCategoryName" class="form-label">Sub Category</label>
                        <input type="text" name="name" id="subCategoryName" class="form-control" placeholder="Enter Sub Category" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Sub Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
