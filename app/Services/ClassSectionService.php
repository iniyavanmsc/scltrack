<?php

namespace App\Services;

use App\Models\Tenant\ClassSection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ClassSectionService
{
    public function getPaginatedClassSections(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return ClassSection::query()
            ->when($search, function ($query, $searchText) {
                $query->where('class_name', 'like', '%' . $searchText . '%')
                    ->orWhere('section', 'like', '%' . $searchText . '%');
            })
            ->orderBy('class_name')
            ->orderBy('section')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getActiveClassSections()
    {
        return ClassSection::query()
            ->where('is_active', true)
            ->orderBy('class_name')
            ->orderBy('section')
            ->get();
    }

    public function createClassSection(array $data): ClassSection
    {
        return ClassSection::create($this->prepareData($data, true));
    }

    public function updateClassSection(ClassSection $classSection, array $data): ClassSection
    {
        DB::connection('tenant')->transaction(function () use ($classSection, $data) {
            $classSection->update($this->prepareData($data, false));

            $classSection->students()->update([
                'class_name' => $classSection->class_name,
                'section' => $classSection->section,
            ]);
        });

        return $classSection->fresh();
    }

    public function deleteClassSection(ClassSection $classSection): void
    {
        if ($classSection->students()->exists()) {
            throw ValidationException::withMessages([
                'class_section' => 'This class / section is added to the student..first delete the student and then delete the class / section',
            ]);
        }

        $classSection->delete();
    }

    private function prepareData(array $data, bool $isCreate): array
    {
        $prepared = [
            'class_name' => trim($data['class_name']),
            'section' => ! empty($data['section']) ? trim($data['section']) : '',
            'is_active' => (bool) ($data['is_active'] ?? true),
            'updated_by_id' => $data['updated_by_id'] ?? null,
        ];

        if ($isCreate) {
            $prepared['created_by_id'] = $data['created_by_id'] ?? null;
        }

        return $prepared;
    }
}
