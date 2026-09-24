@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="module-title-header mb-3">Edit Admin / Driver</div>

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
        $selectedRole = old('user_role', $adminAndDriver->user_role);
        $showDriverFields = $selectedRole === 'cab drivers';
    @endphp

    <form method="POST" action="{{ route('admin-and-drivers.update', $adminAndDriver) }}" data-admin-driver-form>
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $adminAndDriver->full_name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $adminAndDriver->phone) }}" inputmode="numeric" pattern="[0-9]+">
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Email</label>
                <input type="email" name="email_id" class="form-control" value="{{ old('email_id', $adminAndDriver->email_id) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Username <span class="text-danger">*</span></label>
                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $adminAndDriver->username) }}" maxlength="255" autocomplete="username" required>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">User Role <span class="text-danger">*</span></label>
                <select name="user_role" class="form-select" data-user-role-select required>
                    <option value="">Select User Role</option>
                    @foreach ($userRoles as $userRole)
                        <option value="{{ $userRole }}" {{ $selectedRole === $userRole ? 'selected' : '' }}>{{ ucwords($userRole) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Status <span class="text-danger">*</span></label>
                <select name="is_active" class="form-select" required>
                    <option value="1" {{ old('is_active', $adminAndDriver->is_active) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active', $adminAndDriver->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-12">
                <div class="row g-3 {{ $showDriverFields ? '' : 'd-none' }}" data-driver-license-fields>
                    <div class="col-md-6">
                        <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">License Number</label>
                        <input type="text" name="license_number" class="form-control" value="{{ old('license_number', $adminAndDriver->license_number) }}" {{ $showDriverFields ? '' : 'disabled' }}>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">License Expiry Date</label>
                        <input type="date" name="license_expiry_date" class="form-control" value="{{ old('license_expiry_date', $adminAndDriver->license_expiry_date?->format('Y-m-d')) }}" {{ $showDriverFields ? '' : 'disabled' }}>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Address</label>
                <textarea name="address" class="form-control" rows="4">{{ old('address', $adminAndDriver->address) }}</textarea>
            </div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin-and-drivers.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
