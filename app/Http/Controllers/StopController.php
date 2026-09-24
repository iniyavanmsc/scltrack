<?php

namespace App\Http\Controllers;

use App\Models\Tenant\Stop;
use App\Models\Tenant\VehicleRoute;
use App\Services\StopService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StopController extends Controller
{
    public function __construct(private StopService $stopService)
    {
    }

    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $perPage = (int) $request->input('per_page', 10);

        if (! in_array($perPage, [5, 10, 25, 50], true)) {
            $perPage = 10;
        }

        return view('stops.index', [
            'stops' => $this->stopService->getPaginatedStops($search, $perPage),
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function create(): View
    {
        return view('stops.create', [
            'routes' => $this->getRoutesForForm(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $validated['created_by_id'] = $this->currentAdminAndDriverId($request);

        $this->stopService->createStop($validated);

        return redirect()
            ->route('stops.index')
            ->with('success', 'Stop created successfully.');
    }

    public function show(Stop $stop): View
    {
        $stop->load(['route', 'createdBy', 'updatedBy']);

        return view('stops.show', [
            'stop' => $stop,
        ]);
    }

    public function edit(Stop $stop): View
    {
        return view('stops.edit', [
            'stop' => $stop,
            'routes' => $this->getRoutesForForm($stop),
        ]);
    }

    public function update(Request $request, Stop $stop): RedirectResponse
    {
        $validated = $request->validate($this->rules($stop->id));
        $validated['updated_by_id'] = $this->currentAdminAndDriverId($request);

        $this->stopService->updateStop($stop, $validated);

        return redirect()
            ->route('stops.index')
            ->with('success', 'Stop updated successfully.');
    }

    public function destroy(Stop $stop): RedirectResponse
    {
        $this->stopService->deleteStop($stop);

        return redirect()
            ->route('stops.index')
            ->with('success', 'Stop deleted successfully.');
    }

    private function rules(?int $stopId = null): array
    {
        return [
            'route_id' => ['required', 'integer', 'exists:tenant.vehicle_routes,id'],
            'stop_name' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'stop_order' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('tenant.stops', 'stop_order')
                    ->where(fn ($query) => $query->where('route_id', request('route_id')))
                    ->ignore($stopId),
            ],
            'is_active' => ['required', 'boolean'],
        ];
    }

    private function getRoutesForForm(?Stop $stop = null)
    {
        return VehicleRoute::query()
            ->withMax('stops', 'stop_order')
            ->where(function ($query) use ($stop) {
                $query->where('is_active', true);

                if ($stop && $stop->route_id) {
                    $query->orWhere('id', $stop->route_id);
                }
            })
            ->orderBy('route_name')
            ->get();
    }
}
