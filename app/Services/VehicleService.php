<?php

namespace App\Services;

use App\Models\Tenant\Vehicle;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class VehicleService
{
    public function getPaginatedVehicles(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Vehicle::query()
            ->when($search, function ($query, $searchText) {
                $query->where('vehicle_number', 'like', '%' . $searchText . '%')
                    ->orWhere('registration_number', 'like', '%' . $searchText . '%');
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function createVehicle(array $data): Vehicle
    {
        return Vehicle::create($this->prepareData($data));
    }

    public function updateVehicle(Vehicle $vehicle, array $data): Vehicle
    {
        $vehicle->update($this->prepareData($data));

        return $vehicle->fresh();
    }

    public function deleteVehicle(Vehicle $vehicle): void
    {
        if ($vehicle->driverRouteAssignments()->exists()
            || $vehicle->activeTrips()->exists()
            || $vehicle->liveVehicleLocation()->exists()
            || $vehicle->vehicleLocationHistory()->exists()) {
            throw ValidationException::withMessages([
                'vehicle' => 'This vehicle is already used in another record..first remove those records and then delete the vehicle',
            ]);
        }

        $vehicle->delete();
    }

    private function prepareData(array $data): array
    {
        $prepared = [
            'vehicle_number' => trim($data['vehicle_number']),
            'registration_number' => $data['registration_number'] ? trim($data['registration_number']) : null,
            'is_active' => (bool) ($data['is_active'] ?? true),
            'updated_by_id' => $data['updated_by_id'] ?? null,
        ];

        if (array_key_exists('created_by_id', $data)) {
            $prepared['created_by_id'] = $data['created_by_id'];
        }

        return $prepared;
    }
}
