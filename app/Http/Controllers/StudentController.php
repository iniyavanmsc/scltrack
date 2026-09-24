<?php

namespace App\Http\Controllers;

use App\Models\Tenant\ClassSection;
use App\Models\Tenant\ParentModel;
use App\Models\Tenant\Student;
use App\Services\ClassSectionService;
use App\Services\StudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function __construct(
        private StudentService $studentService,
        private ClassSectionService $classSectionService
    )
    {
    }

    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $perPage = (int) $request->input('per_page', 10);

        if (! in_array($perPage, [5, 10, 25, 50], true)) {
            $perPage = 10;
        }

        $students = $this->studentService->getPaginatedStudents($search, $perPage);

        return view('students.index', [
            'students' => $students,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function create(): View
    {
        return view('students.create', [
            'parents' => ParentModel::orderBy('full_name')->get(),
            'classSections' => $this->classSectionService->getActiveClassSections(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $validated['created_by_id'] = $this->currentAdminAndDriverId($request);

        $this->studentService->createStudent($validated);

        return redirect()
            ->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    public function show(Student $student): View
    {
        $student->load(['parent', 'classSection', 'createdBy', 'updatedBy']);

        return view('students.show', [
            'student' => $student,
        ]);
    }

    public function edit(Student $student): View
    {
        return view('students.edit', [
            'student' => $student,
            'parents' => ParentModel::orderBy('full_name')->get(),
            'classSections' => ClassSection::query()
                ->where('is_active', true)
                ->when($student->class_section_id, fn ($query) => $query->orWhere('id', $student->class_section_id))
                ->orderBy('class_name')
                ->orderBy('section')
                ->get(),
        ]);
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate($this->rules($student->id));
        $validated['updated_by_id'] = $this->currentAdminAndDriverId($request);

        $this->studentService->updateStudent($student, $validated);

        return redirect()
            ->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $this->studentService->deleteStudent($student);

        return redirect()
            ->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }

    private function rules(?int $studentId = null): array
    {
        return [
            'admission_no' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('tenant.students', 'admission_no')->ignore($studentId),
            ],
            'full_name' => ['required', 'string', 'max:255'],
            'parent_id' => ['required', 'integer', 'exists:tenant.parents,id'],
            'class_section_id' => ['required', 'integer', 'exists:tenant.class_sections,id'],
            'pickup_address' => ['nullable', 'string'],
            'drop_address' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
