<?php

namespace App\Services;

use App\Models\Tenant\VehicleRoute;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RouteService
{
    public function getPaginatedRoutes(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return VehicleRoute::query()
            ->when($search, function ($query, $searchText) {
                $query->where('route_name', 'like', '%' . $searchText . '%')
                    ->orWhere('route_code', 'like', '%' . $searchText . '%')
                    ->orWhere('trip_type', 'like', '%' . $searchText . '%')
                    ->orWhere('start_location', 'like', '%' . $searchText . '%')
                    ->orWhere('end_location', 'like', '%' . $searchText . '%');
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function createRoute(array $data): VehicleRoute
    {
        return VehicleRoute::create($this->prepareData($data));
    }

    public function updateRoute(VehicleRoute $route, array $data): VehicleRoute
    {
        $route->update($this->prepareData($data, $route));

        return $route->fresh();
    }

    public function deleteRoute(VehicleRoute $route): void
    {
        if ($route->driverRouteAssignments()->exists()
            || $route->studentRouteAssignments()->exists()
            || $route->activeTrips()->exists()
            || $route->stops()->exists()) {
            throw ValidationException::withMessages([
                'route' => 'This route is already used in another record..first remove those records and then delete the route',
            ]);
        }

        $route->delete();
    }

    private function prepareData(array $data, ?VehicleRoute $route = null): array
    {
        $routeName = trim($data['route_name']);
        $routeCode = $route && $route->route_name === $routeName && $route->route_code
            ? $route->route_code
            : $this->generateRouteCode($routeName, $route?->id);

        $prepared = [
            'route_name' => $routeName,
            'route_code' => $routeCode,
            'trip_type' => $data['trip_type'],
            'start_location' => $data['start_location'] ?: null,
            'end_location' => $data['end_location'] ?: null,
            'is_active' => (bool) ($data['is_active'] ?? true),
            'updated_by_id' => $data['updated_by_id'] ?? null,
        ];

        if (array_key_exists('created_by_id', $data)) {
            $prepared['created_by_id'] = $data['created_by_id'];
        }

        return $prepared;
    }

    private function generateRouteCode(string $routeName, ?int $ignoreRouteId = null): string
    {
        $cleanName = preg_replace('/[^A-Za-z0-9]/', '', Str::ascii($routeName)) ?: 'RTE';
        $base = strtoupper(substr($cleanName, 0, 3));
        $base = str_pad($base, 3, 'X');

        $usedCodes = VehicleRoute::query()
            ->where('route_code', 'like', $base . '%')
            ->when($ignoreRouteId, fn ($query) => $query->whereKeyNot($ignoreRouteId))
            ->pluck('route_code')
            ->all();

        for ($sequence = 1; $sequence <= 9999; $sequence++) {
            $code = $base . str_pad((string) $sequence, 2, '0', STR_PAD_LEFT);

            if (! in_array($code, $usedCodes, true)) {
                return $code;
            }
        }

        throw new \RuntimeException('Unable to generate a unique route code.');
    }
}
