<?php

namespace App\Http\Controllers;

use App\Models\Tenant\ClassSection;
use App\Services\ClassSectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClassSectionController extends Controller
{
    public function __construct(private ClassSectionService $classSectionService)
    {
    }

    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $perPage = (int) $request->input('per_page', 10);

        if (! in_array($perPage, [5, 10, 25, 50], true)) {
            $perPage = 10;
        }

        return view('class_sections.index', [
            'classSections' => $this->classSectionService->getPaginatedClassSections($search, $perPage),
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function create(): View
    {
        return view('class_sections.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeSection($request);

        $validated = $request->validate($this->rules());
        $validated['created_by_id'] = $this->currentAdminAndDriverId($request);

        $this->classSectionService->createClassSection($validated);

        return redirect()
            ->route('class-sections.index')
            ->with('success', 'Class / section created successfully.');
    }

    public function show(ClassSection $classSection): View
    {
        $classSection->load(['createdBy', 'updatedBy']);

        return view('class_sections.show', [
            'classSection' => $classSection,
        ]);
    }

    public function edit(ClassSection $classSection): View
    {
        return view('class_sections.edit', [
            'classSection' => $classSection,
        ]);
    }

    public function update(Request $request, ClassSection $classSection): RedirectResponse
    {
        $this->normalizeSection($request);

        $validated = $request->validate($this->rules($classSection->id));
        $validated['updated_by_id'] = $this->currentAdminAndDriverId($request);

        $this->classSectionService->updateClassSection($classSection, $validated);

        return redirect()
            ->route('class-sections.index')
            ->with('success', 'Class / section updated successfully.');
    }

    public function destroy(ClassSection $classSection): RedirectResponse
    {
        $this->classSectionService->deleteClassSection($classSection);

        return redirect()
            ->route('class-sections.index')
            ->with('success', 'Class / section deleted successfully.');
    }

    private function rules(?int $classSectionId = null): array
    {
        return [
            'class_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tenant.class_sections', 'class_name')
                    ->where(fn ($query) => $query->where('section', request('section', '')))
                    ->ignore($classSectionId),
            ],
            'section' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    private function normalizeSection(Request $request): void
    {
        $request->merge([
            'class_name' => trim((string) $request->input('class_name')),
            'section' => trim((string) $request->input('section')),
        ]);
    }
}
