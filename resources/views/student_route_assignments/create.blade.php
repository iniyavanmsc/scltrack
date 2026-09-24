@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="module-title-header mb-0">Add Student Route Assignment</div>
        <a href="{{ route('student-route-assignments.index') }}" class="btn btn-secondary">Back</a>
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

    <form method="POST" action="{{ route('student-route-assignments.store') }}">
        @csrf
        <div class="row g-3">
            @php
                $selectedClassSectionId = old('class_section_id');
                $selectedClassSection = $classSections->firstWhere('id', (int) $selectedClassSectionId);
                $selectedStudentId = old('student_id');
                $selectedStudent = $students->firstWhere('id', (int) $selectedStudentId);
                $selectedRouteId = old('route_id');
                $selectedRoute = $routes->firstWhere('id', (int) $selectedRouteId);
                $selectedStopId = old('stop_id');
                $selectedStop = $stops->firstWhere('id', (int) $selectedStopId);
            @endphp

            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Class / Section <span class="text-danger">*</span></label>
                <div class="position-relative" data-searchable-dropdown data-url="{{ route('lookups.class-sections') }}">
                    <input type="text" class="form-control" data-search-input value="{{ $selectedClassSection?->display_name }}" placeholder="Search class / section" autocomplete="off" required>
                    <input type="hidden" name="class_section_id" id="assignment-class-section-id" data-value-input value="{{ $selectedClassSectionId }}">
                    <div class="list-group searchable-dropdown-results shadow-sm d-none" data-search-results></div>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Student <span class="text-danger">*</span></label>
                <div class="position-relative" data-searchable-dropdown data-url="{{ route('lookups.students') }}" data-parent="#assignment-class-section-id" data-parent-param="class_section_id" data-parent-required="true">
                    <input type="text" class="form-control" data-search-input value="{{ $selectedStudent ? $selectedStudent->full_name . ($selectedStudent->admission_no ? ' - ' . $selectedStudent->admission_no : '') : '' }}" placeholder="Search student" autocomplete="off" required>
                    <input type="hidden" name="student_id" id="assignment-student-id" data-value-input value="{{ $selectedStudentId }}">
                    <div class="list-group searchable-dropdown-results shadow-sm d-none" data-search-results></div>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Route</label>
                <div class="position-relative" data-searchable-dropdown data-url="{{ route('lookups.routes') }}">
                    <input type="text" class="form-control" data-search-input value="{{ $selectedRoute?->display_name }}" placeholder="Search route" autocomplete="off">
                    <input type="hidden" name="route_id" id="assignment-route-id" data-value-input value="{{ $selectedRouteId }}">
                    <div class="list-group searchable-dropdown-results shadow-sm d-none" data-search-results></div>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Stop</label>
                <div class="position-relative" data-searchable-dropdown data-url="{{ route('lookups.stops') }}" data-parent="#assignment-route-id" data-parent-param="route_id" data-parent-required="true">
                    <input type="text" class="form-control" data-search-input value="{{ $selectedStop ? $selectedStop->stop_name . ($selectedStop->route ? ' - ' . $selectedStop->route->route_name : '') : '' }}" placeholder="Search stop" autocomplete="off">
                    <input type="hidden" name="stop_id" id="assignment-stop-id" data-value-input value="{{ $selectedStopId }}">
                    <div class="list-group searchable-dropdown-results shadow-sm d-none" data-search-results></div>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Assigned From</label>
                <input type="date" name="assigned_from" class="form-control" value="{{ old('assigned_from') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Assigned To</label>
                <input type="date" name="assigned_to" class="form-control" value="{{ old('assigned_to') }}">
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
                    <input type="hidden" name="is_active" id="assignment-status" data-value-input value="{{ $selectedStatus }}">
                    <div class="list-group searchable-dropdown-results shadow-sm d-none" data-search-results></div>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('student-route-assignments.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
