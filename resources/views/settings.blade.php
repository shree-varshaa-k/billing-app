@extends('layouts.layout')

@section('content')
<style>
/* For small devices (phones, 320px - 575px) */
@media (max-width: 575px) {
    .container {
        padding: 2rem 1rem !important;
    }
    .bg-white {
        padding: 2rem !important;
    }
    img[alt="Profile Logo"] {
        width: 80px !important;
        height: 80px !important;
    }
}

/* For medium devices (tablets, 576px - 991px) */
@media (min-width: 576px) and (max-width: 991px) {
    .bg-white {
        padding: 3rem !important;
    }
    img[alt="Profile Logo"] {
        width: 90px !important;
        height: 90px !important;
    }
}

/* For large devices (992px and up) */
@media (min-width: 992px) {
    .bg-white {
        max-width: 550px;
        padding: 4rem !important;
    }
}
</style>
<div class="container py-5 d-flex justify-content-center">
    <div class="bg-white p-5 rounded-4 shadow-sm" style="max-width: 550px; width: 100%;">
        
        <!-- Header -->
        <div class="text-center mb-4">
            <div class="position-relative d-inline-block">
                @php
                    $logo = $user->logo ? asset($user->logo) : asset('default-logo.png');
                @endphp

                <img src="{{ $logo }}" 
                     alt="Profile Logo" 
                     class="rounded-circle border border-3 border-primary-subtle shadow-sm" 
                     style="width: 100px; height: 100px; object-fit: cover;">
                <div class="mt-2 fw-bold text-primary">Update Profile</div>
            </div>
        </div>

        <!-- Forms -->
        <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
            @csrf

            <!-- Username -->
            <div class="form-floating mb-3">
                <input 
                    type="text" 
                    name="username" 
                    id="username" 
                    value="{{ old('username', $user->username) }}" 
                    class="form-control border-0 border-bottom border-primary-subtle shadow-none rounded-0" 
                    placeholder="Username" 
                    required>
                <label for="username" class="text-muted">Username</label>
            </div>

            <!-- Password -->
            <div class="form-floating mb-3">
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="form-control border-0 border-bottom border-primary-subtle shadow-none rounded-0" 
                    placeholder="New Password">
                <label for="password" class="text-muted">New Password (optional)</label>
            </div>

            <!-- Logo Upload -->
            <div class="mb-4">
                <label for="logo" class="form-label fw-semibold text-muted">Upload New Logo</label>
                <input type="file" name="logo" id="logo" class="form-control border-0 border-bottom border-primary-subtle shadow-none rounded-0">
            </div>

            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary rounded-pill py-2 fw-semibold">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
