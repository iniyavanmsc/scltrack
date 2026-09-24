<?php

namespace App\Http\Controllers;

use App\Models\Tenant\ClassSection;
use App\Models\Tenant\Stop;
use App\Models\Tenant\Student;
use App\Models\Tenant\StudentRouteAssignment;
use App\Models\Tenant\VehicleRoute;
use App\Services\StudentRouteAssignmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentRouteAssignmentController extends Controller
{
    public function __construct(private StudentRouteAssignmentService $assignmentService)
    {
    }

    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $perPage = (int) $request->input('per_page', 10);

        if (! in_array($perPage, [5, 10, 25, 50], true)) {
            $perPage = 10;
        }

        return view('student_route_assignments.index', [
            'assignments' => $this->assignmentService->getPaginatedAssignments($search, $perPage),
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function create(): View
    {
        return view('student_route_assignments.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $validated['created_by_id'] = $this->currentAdminAndDriverId($request);

        $this->assignmentService->createAssignment($validated);

        return redirect()
            ->route('student-route-assignments.index')
            ->with('success', 'Student route assignment created successfully.');
    }

    public function show(StudentRouteAssignment $studentRouteAssignment): View
    {
        $studentRouteAssignment->load(['student', 'route', 'stop', 'createdBy', 'updatedBy']);

        return view('student_route_assignments.show', [
            'assignment' => $studentRouteAssignment,
        ]);
    }

    public function edit(StudentRouteAssignment $studentRouteAssignment): View
    {
        return view('student_route_assignments.edit', array_merge(
            ['assignment' => $studentRouteAssignment],
            $this->formData($studentRouteAssignment)
        ));
    }

    public function update(Request $request, StudentRouteAssignment $studentRouteAssignment): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $validated['updated_by_id'] = $this->currentAdminAndDriverId($request);

        $this->assignmentService->updateAssignment($studentRouteAssignment, $validated);

        return redirect()
            ->route('student-route-assignments.index')
            ->with('success', 'Student route assignment updated successfully.');
    }

    public function destroy(StudentRouteAssignment $studentRouteAssignment): RedirectResponse
    {
        $this->assignmentService->deleteAssignment($studentRouteAssignment);

        return redirect()
            ->route('student-route-assignments.index')
            ->with('success', 'Student route assignment deleted successfully.');
    }

    private function rules(): array
    {
        return [
            'student_id' => ['required', 'integer', 'exists:tenant.students,id'],
            'route_id' => ['nullable', 'integer', 'exists:tenant.vehicle_routes,id'],
            'stop_id' => ['nullable', 'integer', 'exists:tenant.stops,id'],
            'assigned_from' => ['nullable', 'date'],
            'assigned_to' => ['nullable', 'date', 'after_or_equal:assigned_from'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    private function formData(?StudentRouteAssignment $assignment = null): array
    {
        return [
            'classSections' => ClassSection::query()
                ->where('is_active', true)
                ->orderBy('class_name')
                ->orderBy('section')
                ->get(),
            'students' => Student::query()
                ->where(function ($query) use ($assignment) {
                    $query->where('is_active', true);

                    if ($assignment && $assignment->student_id) {
                        $query->orWhere('id', $assignment->student_id);
                    }
                })
                ->orderBy('full_name')
                ->get(),
            'routes' => VehicleRoute::query()
                ->where(function ($query) use ($assignment) {
                    $query->where('is_active', true);

                    if ($assignment && $assignment->route_id) {
                        $query->orWhere('id', $assignment->route_id);
                    }
                })
                ->orderBy('route_name')
                ->get(),
            'stops' => Stop::query()
                ->with('route')
                ->where(function ($query) use ($assignment) {
                    $query->where('is_active', true);

                    if ($assignment && $assignment->stop_id) {
                        $query->orWhere('id', $assignment->stop_id);
                    }
                })
                ->orderBy('stop_name')
                ->get(),
        ];
    }
}
