<?php

namespace App\Http\Controllers;

use App\Models\Tenant\VehicleRoute;
use App\Services\RouteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RouteController extends Controller
{
    public function __construct(private RouteService $routeService)
    {
    }

    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $perPage = (int) $request->input('per_page', 10);

        if (! in_array($perPage, [5, 10, 25, 50], true)) {
            $perPage = 10;
        }

        $routes = $this->routeService->getPaginatedRoutes($search, $perPage);

        return view('routes.index', [
            'routes' => $routes,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function create(): View
    {
        return view('routes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $validated['created_by_id'] = $this->currentAdminAndDriverId($request);

        $this->routeService->createRoute($validated);

        return redirect()
            ->route('vehicle-routes.index')
            ->with('success', 'Route created successfully.');
    }

    public function show(VehicleRoute $route): View
    {
        $route->load(['createdBy', 'updatedBy']);

        return view('routes.show', [
            'route' => $route,
        ]);
    }

    public function edit(VehicleRoute $route): View
    {
        return view('routes.edit', [
            'route' => $route,
        ]);
    }

    public function update(Request $request, VehicleRoute $route): RedirectResponse
    {
        $validated = $request->validate($this->rules($route->id));
        $validated['updated_by_id'] = $this->currentAdminAndDriverId($request);

        $this->routeService->updateRoute($route, $validated);

        return redirect()
            ->route('vehicle-routes.index')
            ->with('success', 'Route updated successfully.');
    }

    public function destroy(VehicleRoute $route): RedirectResponse
    {
        $this->routeService->deleteRoute($route);

        return redirect()
            ->route('vehicle-routes.index')
            ->with('success', 'Route deleted successfully.');
    }

    private function rules(?int $routeId = null): array
    {
        return [
            'route_name' => ['required', 'string', 'max:255'],
            'trip_type' => ['required', Rule::in(['Morning', 'Afternoon', 'Evening', 'Special Trips'])],
            'start_location' => ['nullable', 'string', 'max:255'],
            'end_location' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
