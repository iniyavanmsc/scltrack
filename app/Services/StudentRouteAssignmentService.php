<?php

namespace App\Services;

use App\Models\Tenant\StudentRouteAssignment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StudentRouteAssignmentService
{
    public function getPaginatedAssignments(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return StudentRouteAssignment::query()
            ->with(['student', 'route', 'stop'])
            ->when($search, function ($query, $searchText) {
                $query->where(function ($searchQuery) use ($searchText) {
                    $searchQuery->whereHas('student', function ($studentQuery) use ($searchText) {
                        $studentQuery->where('full_name', 'like', '%' . $searchText . '%')
                            ->orWhere('admission_no', 'like', '%' . $searchText . '%');
                    })
                        ->orWhereHas('route', function ($routeQuery) use ($searchText) {
                            $routeQuery->where('route_name', 'like', '%' . $searchText . '%')
                                ->orWhere('route_code', 'like', '%' . $searchText . '%')
                                ->orWhere('trip_type', 'like', '%' . $searchText . '%');
                        })
                        ->orWhereHas('stop', function ($stopQuery) use ($searchText) {
                            $stopQuery->where('stop_name', 'like', '%' . $searchText . '%')
                                ->orWhere('stop_code', 'like', '%' . $searchText . '%');
                        });
                });
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function createAssignment(array $data): StudentRouteAssignment
    {
        return StudentRouteAssignment::create($this->prepareData($data));
    }

    public function updateAssignment(StudentRouteAssignment $assignment, array $data): StudentRouteAssignment
    {
        $assignment->update($this->prepareData($data));

        return $assignment->fresh(['student', 'route', 'stop']);
    }

    public function deleteAssignment(StudentRouteAssignment $assignment): void
    {
        $assignment->delete();
    }

    private function prepareData(array $data): array
    {
        $prepared = [
            'student_id' => $data['student_id'],
            'route_id' => ($data['route_id'] ?? null) ?: null,
            'stop_id' => ($data['stop_id'] ?? null) ?: null,
            'assigned_from' => ($data['assigned_from'] ?? null) ?: null,
            'assigned_to' => ($data['assigned_to'] ?? null) ?: null,
            'is_active' => (bool) ($data['is_active'] ?? true),
            'updated_by_id' => $data['updated_by_id'] ?? null,
        ];

        if (array_key_exists('created_by_id', $data)) {
            $prepared['created_by_id'] = $data['created_by_id'];
        }

        return $prepared;
    }
}
