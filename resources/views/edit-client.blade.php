@extends('layouts.layout')
@section('title', 'Edit Client')

@section('content')
<style>
/* ==============================
   RESPONSIVE MEDIA QUERIES
   ============================== */

/* For small screens (phones, up to 576px) */
@media (max-width: 576px) {
    .edit-client-form {
        padding: 1rem !important;
    }
    h3 {
        font-size: 1.25rem;
        text-align: center;
    }
    .btn-sm {
        font-size: 0.8rem;
    }
    .container-fluid {
        padding: 0 10px;
    }
}

/* For medium screens (tablets, 577px–992px) */
@media (min-width: 577px) and (max-width: 992px) {
    .edit-client-form {
        max-width: 600px;
        margin: 0 auto;
    }
}

/* For large screens (desktops, 993px and up) */
@media (min-width: 993px) {
    .edit-client-form {
        max-width: 700px;
        margin: 0 auto;
    }
}
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary fw-bold">Edit Client</h3>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-sm">← Back</a>
    </div>

    <form action="{{ route('clients.update', $client->id) }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" value="{{ $client->name }}" id="name"
                   class="form-control" pattern="[A-Za-z\s]+"
                   title="Only letters and spaces are allowed" required>
            <small id="name-error" class="text-danger" style="display:none;">Name already exists!</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ $client->email }}" id="email" class="form-control">
            <small id="email-error" class="text-danger" style="display:none;">Email already exists!</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="{{ $client->phone }}" id="edit_phone"
                   class="form-control" pattern="[0-9]+" maxlength="10" title="Only numbers allowed">
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="2">{{ $client->address }}</textarea>
        </div>

        <button type="submit" id="saveBtn" class="btn btn-primary">Update Client</button>
    </form>
</div>

<script>
    // Prevent numbers in Name
    document.getElementById('name').addEventListener('input', function() {
        this.value = this.value.replace(/[^A-Za-z\s]/g, '');
    });

    // Prevent letters in Phone
    document.getElementById('edit_phone').addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>

{{-- ✅ AJAX Duplicate Validation Script --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
    // Debounce helper function
    function debounce(fn, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => fn.apply(this, args), delay);
        };
    }

    function checkDuplicate(field, value) {
        if (!value.trim()) {
            $('#' + field + '-error').hide();
            return;
        }

        $.ajax({
            url: "{{ route('clients.checkDuplicate') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                field: field,
                value: value
            },
            success: function (response) {
                if (response.exists) {
                    $('#' + field + '-error').show();
                    $('#saveBtn').prop('disabled', true);
                } else {
                    $('#' + field + '-error').hide();
                    if (!$('#name-error').is(':visible') && !$('#email-error').is(':visible')) {
                        $('#saveBtn').prop('disabled', false);
                    }
                }
            }
        });
    }

    $('#name').on('input', debounce(function() {
        checkDuplicate('name', $(this).val());
    }, 400));

    $('#email').on('input', debounce(function() {
        checkDuplicate('email', $(this).val());
    }, 400));
});
</script>
@endsection
