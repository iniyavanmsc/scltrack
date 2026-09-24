<?php

namespace App\Http\Controllers;

use App\Models\Tenant\Vehicle;
use App\Services\VehicleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function __construct(private VehicleService $vehicleService)
    {
    }

    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $perPage = (int) $request->input('per_page', 10);

        if (! in_array($perPage, [5, 10, 25, 50], true)) {
            $perPage = 10;
        }

        $vehicles = $this->vehicleService->getPaginatedVehicles($search, $perPage);

        return view('vehicles.index', [
            'vehicles' => $vehicles,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function create(): View
    {
        return view('vehicles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $validated['created_by_id'] = $this->currentAdminAndDriverId($request);

        $this->vehicleService->createVehicle($validated);

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Vehicle created successfully.');
    }

    public function show(Vehicle $vehicle): View
    {
        $vehicle->load(['createdBy', 'updatedBy']);

        return view('vehicles.show', [
            'vehicle' => $vehicle,
        ]);
    }

    public function edit(Vehicle $vehicle): View
    {
        return view('vehicles.edit', [
            'vehicle' => $vehicle,
        ]);
    }

    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $validated = $request->validate($this->rules($vehicle->id));
        $validated['updated_by_id'] = $this->currentAdminAndDriverId($request);

        $this->vehicleService->updateVehicle($vehicle, $validated);

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $this->vehicleService->deleteVehicle($vehicle);

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }

    private function rules(?int $vehicleId = null): array
    {
        return [
            'vehicle_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tenant.vehicles', 'vehicle_number')->ignore($vehicleId),
            ],
            'registration_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('tenant.vehicles', 'registration_number')->ignore($vehicleId),
            ],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
