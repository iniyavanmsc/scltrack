@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="module-title-header mb-0">Add Admin / Driver</div>
        <a href="{{ route('admin-and-drivers.index') }}" class="btn btn-secondary">Back</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $selectedRole = old('user_role');
        $showDriverFields = $selectedRole === 'cab drivers';
    @endphp

    <form method="POST" action="{{ route('admin-and-drivers.store') }}" data-admin-driver-form>
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" inputmode="numeric" pattern="[0-9]+">
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Email</label>
                <input type="email" name="email_id" class="form-control" value="{{ old('email_id') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Username <span class="text-danger">*</span></label>
                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" maxlength="255" autocomplete="username" required>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Password <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-6">
                @php
                    $userRoleOptions = collect($userRoles)
                        ->map(fn ($userRole) => ['id' => $userRole, 'text' => ucwords($userRole)])
                        ->values();
                @endphp
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">User Role <span class="text-danger">*</span></label>
                <div class="position-relative" data-searchable-dropdown data-options='@json($userRoleOptions)'>
                    <input type="text" class="form-control" data-search-input data-user-role-select value="{{ $selectedRole ? ucwords($selectedRole) : '' }}" placeholder="Search user role" autocomplete="off" required>
                    <input type="hidden" name="user_role" id="admin-driver-user-role" data-value-input value="{{ $selectedRole }}">
                    <div class="list-group searchable-dropdown-results shadow-sm d-none" data-search-results></div>
                </div>
            </div>
            <div class="col-md-6">
                @php
                    $statusOptions = collect([
                        ['id' => '1', 'text' => 'Active'],
                        ['id' => '0', 'text' => 'Inactive'],
                    ]);
                    $selectedStatus = (string) old('is_active', '1');
                @endphp
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Status <span class="text-danger">*</span></label>
                <div class="position-relative" data-searchable-dropdown data-options='@json($statusOptions)'>
                    <input type="text" class="form-control" data-search-input value="{{ $selectedStatus === '1' ? 'Active' : 'Inactive' }}" placeholder="Search status" autocomplete="off" required>
                    <input type="hidden" name="is_active" id="admin-driver-status" data-value-input value="{{ $selectedStatus }}">
                    <div class="list-group searchable-dropdown-results shadow-sm d-none" data-search-results></div>
                </div>
            </div>
            <div class="col-12">
                <div class="row g-3 {{ $showDriverFields ? '' : 'd-none' }}" data-driver-license-fields>
                    <div class="col-md-6">
                        <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">License Number</label>
                        <input type="text" name="license_number" class="form-control" value="{{ old('license_number') }}" {{ $showDriverFields ? '' : 'disabled' }}>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">License Expiry Date</label>
                        <input type="date" name="license_expiry_date" class="form-control" value="{{ old('license_expiry_date') }}" {{ $showDriverFields ? '' : 'disabled' }}>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Address</label>
                <textarea name="address" class="form-control" rows="4">{{ old('address') }}</textarea>
            </div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('admin-and-drivers.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
