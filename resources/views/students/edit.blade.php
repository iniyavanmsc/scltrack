@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="module-title-header mb-3">Edit Student</div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('students.update', $student) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Admission No</label>
                <input type="text" name="admission_no" class="form-control" value="{{ old('admission_no', $student->admission_no) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $student->full_name) }}" required>
            </div>
            <div class="col-md-6">
                @php
                    $selectedParentId = old('parent_id', $student->parent_id);
                    $selectedParent = $parents->firstWhere('id', (int) $selectedParentId);
                    $selectedParentLabel = $selectedParent ? $selectedParent->full_name . ($selectedParent->phone ? ' - ' . $selectedParent->phone : '') : '';
                    $parentOptions = $parents->map(fn ($parentOption) => [
                        'id' => $parentOption->id,
                        'name' => $parentOption->full_name,
                        'phone' => $parentOption->phone,
                    ])->values();
                @endphp
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Parent <span class="text-danger">*</span></label>
                <div class="position-relative parent-search-wrapper" data-parent-search data-parents='@json($parentOptions)'>
                    <input
                        type="text"
                        class="form-control"
                        data-parent-search-input
                        name="parent_search"
                        value="{{ old('parent_search', $selectedParentLabel) }}"
                        placeholder="Search by parent name or phone"
                        autocomplete="off"
                        required
                    >
                    <input type="hidden" name="parent_id" data-parent-id-input value="{{ $selectedParentId }}" required>
                    <div class="list-group parent-search-results shadow-sm d-none" data-parent-results></div>
                </div>
            </div>
            <div class="col-md-6">
                @php
                    $selectedClassSectionId = old('class_section_id', $student->class_section_id);
                    $selectedClassSection = $classSections->firstWhere('id', (int) $selectedClassSectionId);
                    $selectedClassSectionLabel = $selectedClassSection?->display_name ?: '';
                    $classSectionOptions = $classSections->map(fn ($classSectionOption) => [
                        'id' => $classSectionOption->id,
                        'name' => $classSectionOption->display_name,
                    ])->values();
                @endphp
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Class / Section <span class="text-danger">*</span></label>
                <div class="position-relative class-section-search-wrapper" data-class-section-search data-class-sections='@json($classSectionOptions)'>
                    <input
                        type="text"
                        class="form-control"
                        data-class-section-search-input
                        name="class_section_search"
                        value="{{ old('class_section_search', $selectedClassSectionLabel) }}"
                        placeholder="Search class / section"
                        autocomplete="off"
                        required
                    >
                    <input type="hidden" name="class_section_id" data-class-section-id-input value="{{ $selectedClassSectionId }}" required>
                    <div class="list-group class-section-search-results shadow-sm d-none" data-class-section-results></div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Pickup Address</label>
                <textarea name="pickup_address" class="form-control" rows="4">{{ old('pickup_address', $student->pickup_address) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Drop Address</label>
                <textarea name="drop_address" class="form-control" rows="4">{{ old('drop_address', $student->drop_address) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Status <span class="text-danger">*</span></label>
                <select name="is_active" class="form-select" required>
                    <option value="1" {{ old('is_active', $student->is_active) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active', $student->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .parent-search-results,
    .class-section-search-results {
        left: 0;
        max-height: 220px;
        overflow-y: auto;
        position: absolute;
        right: 0;
        top: calc(100% + 4px);
        z-index: 1050;
    }

    .parent-search-results .list-group-item,
    .class-section-search-results .list-group-item {
        cursor: pointer;
    }

    .parent-search-results .parent-phone {
        font-size: 0.78rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('[data-parent-search]').forEach((wrapper) => {
        const parents = JSON.parse(wrapper.dataset.parents || '[]');
        const searchInput = wrapper.querySelector('[data-parent-search-input]');
        const parentIdInput = wrapper.querySelector('[data-parent-id-input]');
        const results = wrapper.querySelector('[data-parent-results]');
        let selectedLabel = searchInput.value;

        const optionLabel = (parent) => parent.phone ? `${parent.name} - ${parent.phone}` : parent.name;

        const hideResults = () => {
            results.classList.add('d-none');
        };

        const selectParent = (parent) => {
            searchInput.value = optionLabel(parent);
            selectedLabel = searchInput.value;
            parentIdInput.value = parent.id;
            searchInput.setCustomValidity('');
            hideResults();
        };

        const renderResults = (clearSelection = false) => {
            const query = searchInput.value.trim().toLowerCase();

            if (clearSelection && searchInput.value !== selectedLabel) {
                parentIdInput.value = '';
            }

            const matches = parents
                .filter((parent) => {
                    const name = (parent.name || '').toLowerCase();
                    const phone = (parent.phone || '').toLowerCase();

                    return !query || name.includes(query) || phone.includes(query);
                })
                .slice(0, 10);

            results.innerHTML = '';

            if (!matches.length) {
                const emptyItem = document.createElement('div');
                emptyItem.className = 'list-group-item text-muted';
                emptyItem.textContent = 'No parent found';
                results.appendChild(emptyItem);
                results.classList.remove('d-none');
                return;
            }

            matches.forEach((parent) => {
                const item = document.createElement('button');
                item.type = 'button';
                item.className = 'list-group-item list-group-item-action';
                const name = document.createElement('div');
                name.className = 'fw-semibold';
                name.textContent = parent.name;
                item.appendChild(name);

                if (parent.phone) {
                    const phone = document.createElement('div');
                    phone.className = 'text-muted parent-phone';
                    phone.textContent = parent.phone;
                    item.appendChild(phone);
                }

                item.addEventListener('mousedown', (event) => {
                    event.preventDefault();
                    selectParent(parent);
                });
                results.appendChild(item);
            });

            results.classList.remove('d-none');
        };

        searchInput.addEventListener('input', () => renderResults(true));
        searchInput.addEventListener('focus', () => renderResults(false));
        searchInput.addEventListener('blur', () => {
            setTimeout(hideResults, 150);
        });

        searchInput.form?.addEventListener('submit', (event) => {
            if (!parentIdInput.value) {
                searchInput.setCustomValidity('Please select a parent from the list.');
                searchInput.reportValidity();
                event.preventDefault();
                return;
            }

            searchInput.setCustomValidity('');
        });
    });

    document.querySelectorAll('[data-class-section-search]').forEach((wrapper) => {
        const classSections = JSON.parse(wrapper.dataset.classSections || '[]');
        const searchInput = wrapper.querySelector('[data-class-section-search-input]');
        const classSectionIdInput = wrapper.querySelector('[data-class-section-id-input]');
        const results = wrapper.querySelector('[data-class-section-results]');
        let selectedLabel = searchInput.value;

        function hideResults() {
            results.classList.add('d-none');
        }

        function selectClassSection(classSection) {
            searchInput.value = classSection.name;
            selectedLabel = classSection.name;
            classSectionIdInput.value = classSection.id;
            searchInput.setCustomValidity('');
            hideResults();
        }

        function showResults() {
            const query = searchInput.value.trim().toLowerCase();

            if (searchInput.value !== selectedLabel) {
                classSectionIdInput.value = '';
            }

            const matches = classSections
                .filter((classSection) => classSection.name.toLowerCase().includes(query))
                .slice(0, 10);

            results.innerHTML = '';

            if (!matches.length) {
                results.innerHTML = '<div class="list-group-item text-muted">No class / section found</div>';
                results.classList.remove('d-none');
                return;
            }

            matches.forEach((classSection) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'list-group-item list-group-item-action';
                button.textContent = classSection.name;
                button.addEventListener('mousedown', function (event) {
                    event.preventDefault();
                    selectClassSection(classSection);
                });
                results.appendChild(button);
            });

            results.classList.remove('d-none');
        }

        searchInput.addEventListener('input', showResults);
        searchInput.addEventListener('focus', showResults);
        searchInput.addEventListener('blur', function () {
            setTimeout(hideResults, 150);
        });

        searchInput.form?.addEventListener('submit', function (event) {
            if (!classSectionIdInput.value) {
                searchInput.setCustomValidity('Please select a class / section from the list.');
                searchInput.reportValidity();
                event.preventDefault();
            }
        });
    });
</script>
@endpush
