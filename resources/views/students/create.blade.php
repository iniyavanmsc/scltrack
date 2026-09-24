@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="module-title-header mb-0">Add Student</div>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">Back</a>
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

    <form method="POST" action="{{ route('students.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Admission No</label>
                <input type="text" name="admission_no" class="form-control" value="{{ old('admission_no') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
            </div>
            <div class="col-md-6">
                @php
                    $selectedParentId = old('parent_id');
                    $selectedParent = $parents->firstWhere('id', (int) $selectedParentId);
                    $selectedParentLabel = $selectedParent ? $selectedParent->full_name . ($selectedParent->phone ? ' - ' . $selectedParent->phone : '') : '';
                    $parentOptions = $parents->map(fn ($parentOption) => [
                        'id' => $parentOption->id,
                        'text' => $parentOption->full_name . ($parentOption->phone ? ' - ' . $parentOption->phone : ''),
                    ])->values();
                @endphp
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Parent <span class="text-danger">*</span></label>
                <div class="position-relative" data-searchable-dropdown data-options='@json($parentOptions)'>
                    <input
                        type="text"
                        class="form-control"
                        data-search-input
                        name="parent_search"
                        value="{{ old('parent_search', $selectedParentLabel) }}"
                        placeholder="Search by parent name or phone"
                        autocomplete="off"
                        required
                    >
                    <input type="hidden" name="parent_id" id="student-parent-id" data-value-input value="{{ $selectedParentId }}">
                    <div class="list-group searchable-dropdown-results shadow-sm d-none" data-search-results></div>
                </div>
            </div>
            <div class="col-md-6">
                @php
                    $selectedClassSectionId = old('class_section_id');
                    $selectedClassSection = $classSections->firstWhere('id', (int) $selectedClassSectionId);
                    $selectedClassSectionLabel = $selectedClassSection?->display_name ?: '';
                    $classSectionOptions = $classSections->map(fn ($classSectionOption) => [
                        'id' => $classSectionOption->id,
                        'text' => $classSectionOption->display_name,
                    ])->values();
                @endphp
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Class / Section <span class="text-danger">*</span></label>
                <div class="position-relative" data-searchable-dropdown data-options='@json($classSectionOptions)'>
                    <input
                        type="text"
                        class="form-control"
                        data-search-input
                        name="class_section_search"
                        value="{{ old('class_section_search', $selectedClassSectionLabel) }}"
                        placeholder="Search class / section"
                        autocomplete="off"
                        required
                    >
                    <input type="hidden" name="class_section_id" id="student-class-section-id" data-value-input value="{{ $selectedClassSectionId }}">
                    <div class="list-group searchable-dropdown-results shadow-sm d-none" data-search-results></div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Pickup Address</label>
                <textarea name="pickup_address" class="form-control" rows="4">{{ old('pickup_address') }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Drop Address</label>
                <textarea name="drop_address" class="form-control" rows="4">{{ old('drop_address') }}</textarea>
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
                    <input type="hidden" name="is_active" id="student-status" data-value-input value="{{ $selectedStatus }}">
                    <div class="list-group searchable-dropdown-results shadow-sm d-none" data-search-results></div>
                </div>
            </div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
