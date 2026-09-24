@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="module-title-header mb-3">Edit Student Route Assignment</div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('student-route-assignments.update', $assignment) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Student <span class="text-danger">*</span></label>
                <select name="student_id" class="form-select" required>
                    <option value="">Select Student</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id', $assignment->student_id) == $student->id ? 'selected' : '' }}>
                            {{ $student->full_name }}{{ $student->admission_no ? ' - ' . $student->admission_no : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Route</label>
                <select name="route_id" id="assignment-route-id" class="form-select">
                    <option value="">Select Route</option>
                    @foreach ($routes as $route)
                        <option value="{{ $route->id }}" {{ old('route_id', $assignment->route_id) == $route->id ? 'selected' : '' }}>
                            {{ $route->display_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Stop</label>
                <select name="stop_id" id="assignment-stop-id" class="form-select" data-stops-url="{{ route('lookups.stops') }}" data-selected-stop-id="{{ old('stop_id', $assignment->stop_id) }}">
                    <option value="">Select Stop</option>
                    @foreach ($stops as $stop)
                        <option value="{{ $stop->id }}" {{ old('stop_id', $assignment->stop_id) == $stop->id ? 'selected' : '' }}>
                            {{ $stop->stop_name }}{{ $stop->route ? ' - ' . $stop->route->route_name : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Assigned From</label>
                <input type="date" name="assigned_from" class="form-control" value="{{ old('assigned_from', $assignment->assigned_from ? $assignment->assigned_from->format('Y-m-d') : '') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Assigned To</label>
                <input type="date" name="assigned_to" class="form-control" value="{{ old('assigned_to', $assignment->assigned_to ? $assignment->assigned_to->format('Y-m-d') : '') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Status <span class="text-danger">*</span></label>
                <select name="is_active" class="form-select" required>
                    <option value="1" {{ old('is_active', $assignment->is_active) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active', $assignment->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('student-route-assignments.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var routeSelect = document.getElementById('assignment-route-id');
    var stopSelect = document.getElementById('assignment-stop-id');

    if (!routeSelect || !stopSelect) {
        return;
    }

    function resetStops(message) {
        stopSelect.innerHTML = '';

        var option = document.createElement('option');
        option.value = '';
        option.textContent = message;
        stopSelect.appendChild(option);
    }

    function loadStops(routeId, selectedStopId) {
        resetStops(routeId ? 'Loading stops...' : 'Select Route First');
        stopSelect.disabled = !routeId;

        if (!routeId) {
            stopSelect.value = '';
            return;
        }

        var params = new URLSearchParams();
        params.set('route_id', routeId);

        if (selectedStopId) {
            params.set('selected_id', selectedStopId);
        }

        fetch(stopSelect.dataset.stopsUrl + '?' + params.toString(), {
            headers: { 'Accept': 'application/json' },
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (stops) {
                resetStops('Select Stop');

                stops.forEach(function (stop) {
                    var option = document.createElement('option');
                    option.value = stop.id;
                    option.textContent = stop.text;
                    stopSelect.appendChild(option);
                });

                stopSelect.value = selectedStopId || '';
                stopSelect.disabled = false;
            })
            .catch(function () {
                resetStops('Unable to load stops');
                stopSelect.disabled = false;
            });
    }

    loadStops(routeSelect.value, stopSelect.dataset.selectedStopId);

    routeSelect.addEventListener('change', function () {
        stopSelect.dataset.selectedStopId = '';
        loadStops(routeSelect.value, '');
    });
});
</script>
@endpush
@endsection
