<?php

namespace App\Services;

use App\Models\Tenant\ClassSection;
use App\Models\Tenant\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class StudentService
{
    public function getPaginatedStudents(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Student::query()
            ->with(['parent', 'classSection'])
            ->when($search, function ($query, $searchText) {
                $query->where('full_name', 'like', '%' . $searchText . '%')
                    ->orWhere('admission_no', 'like', '%' . $searchText . '%')
                    ->orWhere('class_name', 'like', '%' . $searchText . '%')
                    ->orWhere('section', 'like', '%' . $searchText . '%');
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function createStudent(array $data): Student
    {
        return Student::create($this->prepareData($data));
    }

    public function updateStudent(Student $student, array $data): Student
    {
        $student->update($this->prepareData($data));

        return $student->fresh(['parent', 'classSection']);
    }

    public function deleteStudent(Student $student): void
    {
        if ($student->routeAssignments()->exists()) {
            throw ValidationException::withMessages([
                'student' => 'This student is already used in a student route assignment..first remove those records and then delete the student',
            ]);
        }

        $student->delete();
    }

    private function prepareData(array $data): array
    {
        $classSection = ClassSection::findOrFail($data['class_section_id']);

        $prepared = [
            'admission_no' => $data['admission_no'] ?: null,
            'full_name' => $data['full_name'],
            'parent_id' => $data['parent_id'] ?: null,
            'class_section_id' => $classSection->id,
            'class_name' => $classSection->class_name,
            'section' => $classSection->section ?: null,
            'pickup_address' => $data['pickup_address'] ?: null,
            'drop_address' => $data['drop_address'] ?: null,
            'is_active' => (bool) ($data['is_active'] ?? true),
            'updated_by_id' => $data['updated_by_id'] ?? null,
        ];

        if (array_key_exists('created_by_id', $data)) {
            $prepared['created_by_id'] = $data['created_by_id'];
        }

        return $prepared;
    }
}
