@extends('layouts.layout')
@section('title', 'Category')

@section('content')
<div class="container">

    <h3 class="mb-3">Category</h3>

    <!-- Add Category Button -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        Add Category
    </button>

    <!-- Categories Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Category</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $c)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $c->name }}</td>
                <td class="d-flex gap-2">
                    <!-- Edit Button -->
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $c->id }}">
                        Edit
                    </button>

                    <!-- Delete Button -->
                    <form action="{{ route('products.categories.delete', $c->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
                        @csrf 
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>

            <!-- Edit Category Modal -->
            <div class="modal fade" id="editCategoryModal{{ $c->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel{{ $c->id }}" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form method="POST" action="{{ route('products.categories.update', $c->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                      <h5 class="modal-title" id="editCategoryModalLabel{{ $c->id }}">Edit Category</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="categoryName{{ $c->id }}" class="form-label">Category Name</label>
                            <input type="text" name="name" id="categoryName{{ $c->id }}" class="form-control" value="{{ $c->name }}" required>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Update Category</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('products.categories.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label for="categoryName" class="form-label">Category Name</label>
                <input type="text" name="name" id="categoryName" class="form-control" placeholder="Enter Category Name" required>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add Category</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
