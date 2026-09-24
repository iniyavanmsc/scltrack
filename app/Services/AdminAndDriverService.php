<?php

namespace App\Services;

use App\Models\Tenant\AdminAndDriver;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAndDriverService
{
    public const USER_ROLES = [
        'super admin',
        'transport admin',
        'data entry staff',
        'cab drivers',
        'cab assistant',
    ];

    public function getPaginatedAdminAndDrivers(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return AdminAndDriver::query()
            ->when($search, function ($query, $searchText) {
                $query->where('full_name', 'like', '%' . $searchText . '%')
                    ->orWhere('phone', 'like', '%' . $searchText . '%')
                    ->orWhere('email_id', 'like', '%' . $searchText . '%')
                    ->orWhere('username', 'like', '%' . $searchText . '%')
                    ->orWhere('user_role', 'like', '%' . $searchText . '%')
                    ->orWhere('license_number', 'like', '%' . $searchText . '%');
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function createAdminAndDriver(array $data): AdminAndDriver
    {
        return AdminAndDriver::create($this->prepareData($data, true));
    }

    public function updateAdminAndDriver(AdminAndDriver $adminAndDriver, array $data): AdminAndDriver
    {
        $adminAndDriver->update($this->prepareData($data, false));

        return $adminAndDriver->fresh();
    }

    public function deleteAdminAndDriver(AdminAndDriver $adminAndDriver): void
    {
        if ($adminAndDriver->driverRouteAssignments()->exists()
            || $adminAndDriver->activeTrips()->exists()) {
            throw ValidationException::withMessages([
                'admin_and_driver' => 'This admin / driver is already used in another record..first remove those records and then delete the admin / driver',
            ]);
        }

        $adminAndDriver->delete();
    }

    private function prepareData(array $data, bool $isCreate): array
    {
        $isCabDriver = ($data['user_role'] ?? null) === 'cab drivers';

        $prepared = [
            'full_name' => trim($data['full_name']),
            'phone' => ! empty($data['phone']) ? trim($data['phone']) : null,
            'email_id' => ! empty($data['email_id']) ? trim($data['email_id']) : null,
            'username' => trim($data['username']),
            'user_role' => $data['user_role'],
            'license_number' => $isCabDriver && ! empty($data['license_number']) ? trim($data['license_number']) : null,
            'license_expiry_date' => $isCabDriver && ! empty($data['license_expiry_date']) ? $data['license_expiry_date'] : null,
            'address' => ! empty($data['address']) ? $data['address'] : null,
            'is_active' => (bool) ($data['is_active'] ?? true),
            'updated_by_id' => $data['updated_by_id'] ?? null,
        ];

        if ($isCreate) {
            $prepared['created_by_id'] = $data['created_by_id'] ?? null;
        }

        if (! empty($data['password'])) {
            $prepared['password'] = Hash::make($data['password']);
        }

        return $prepared;
    }
}
