<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Student;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function __construct(private StudentService $studentService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $search = $request->string('search')->toString();
        $perPage = (int) $request->input('per_page', 10);

        if (! in_array($perPage, [5, 10, 25, 50], true)) {
            $perPage = 10;
        }

        $students = $this->studentService->getPaginatedStudents($search, $perPage);

        return response()->json($students);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules());
        $validated['created_by_id'] = null;

        $student = $this->studentService->createStudent($validated);

        return response()->json([
            'message' => 'Student created successfully.',
            'data' => $student,
        ], 201);
    }

    public function show(Student $student): JsonResponse
    {
        return response()->json($student->load(['parent', 'classSection']));
    }

    public function update(Request $request, Student $student): JsonResponse
    {
        $validated = $request->validate($this->rules($student->id));

        $student = $this->studentService->updateStudent($student, $validated);

        return response()->json([
            'message' => 'Student updated successfully.',
            'data' => $student,
        ]);
    }

    public function destroy(Student $student): JsonResponse
    {
        $this->studentService->deleteStudent($student);

        return response()->json([
            'message' => 'Student deleted successfully.',
        ]);
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
