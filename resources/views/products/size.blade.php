@extends('layouts.layout')
@section('title', 'Size')

@section('content')
<div class="container">

    <h3 class="mb-3">Size</h3>

    <!-- Add Size Button -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addSizeModal">
        Add kg
    </button>

    <!-- Sizes Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>kg</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sizes as $s)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $s->name }}</td>
                <td class="d-flex gap-2">

                    <!-- Edit Button -->
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSizeModal{{ $s->id }}">
                        Edit
                    </button>

                    <!-- Delete Button -->
                    <form method="POST" action="{{ route('products.sizes.delete', $s->id) }}" onsubmit="return confirm('Are you sure you want to delete this size?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>

            <!-- Edit Size Modal -->
            <div class="modal fade" id="editSizeModal{{ $s->id }}" tabindex="-1" aria-labelledby="editSizeModalLabel{{ $s->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('products.sizes.update', $s->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title" id="editSizeModalLabel{{ $s->id }}">Edit Size</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="sizeName{{ $s->id }}" class="form-label">Size</label>
                                    <input type="text" name="name" id="sizeName{{ $s->id }}" class="form-control" value="{{ $s->name }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update Size</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Size Modal -->
<div class="modal fade" id="addSizeModal" tabindex="-1" aria-labelledby="addSizeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('products.sizes.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addSizeModalLabel">Add Size</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="sizeName" class="form-label">Size</label>
                        <input type="text" name="name" id="sizeName" class="form-control" placeholder="Enter Size" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Size</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
