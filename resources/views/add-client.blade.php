@extends('layouts.layout')
@section('title', 'Add Client')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary fw-bold">Add Client</h3>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-sm">← Back to Clients</a>
    </div>

    <form action="{{ route('clients.store') }}" method="POST" class="card p-4 shadow-sm" id="clientForm">
        @csrf

        {{-- Client Name --}}
        <div class="mb-3">
            <label class="form-label">Client Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                   class="form-control"
                   placeholder="Enter client name"
                   required pattern="[A-Za-z\s]+"
                   title="Only letters and spaces allowed">
            <small id="name-error" class="text-danger" style="display:none;">This client name already exists.</small>
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}"
                   class="form-control"
                   placeholder="Enter email address">
            <small id="email-error" class="text-danger" style="display:none;">This email address already exists.</small>
        </div>

        {{-- Phone --}}
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" id="phone"
                   value="{{ old('phone') }}"
                   class="form-control"
                   placeholder="Enter phone number"
                   maxlength="10"
                   inputmode="numeric"
                   pattern="[0-9]{10}"
                   title="Enter a valid 10-digit number"
                   onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                   oninput="if(this.value.length > 10) this.value = this.value.slice(0, 10);">
        </div>

        {{-- Address --}}
        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="2"
                      placeholder="Enter address">{{ old('address') }}</textarea>
        </div>

        <button type="submit" id="saveBtn" class="btn btn-primary">Save Client</button>
    </form>
</div>

{{-- ✅ AJAX Script --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
    // Helper function for debounce (waits for user to stop typing)
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
