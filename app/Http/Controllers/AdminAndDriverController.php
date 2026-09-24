<?php

namespace App\Http\Controllers;

use App\Models\Tenant\AdminAndDriver;
use App\Services\AdminAndDriverService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminAndDriverController extends Controller
{
    public function __construct(private AdminAndDriverService $adminAndDriverService)
    {
    }

    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $perPage = (int) $request->input('per_page', 10);

        if (! in_array($perPage, [5, 10, 25, 50], true)) {
            $perPage = 10;
        }

        $adminAndDrivers = $this->adminAndDriverService->getPaginatedAdminAndDrivers($search, $perPage);

        return view('admin_and_drivers.index', [
            'adminAndDrivers' => $adminAndDrivers,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function create(): View
    {
        return view('admin_and_drivers.create', [
            'userRoles' => AdminAndDriverService::USER_ROLES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeUsername($request);

        $validated = $request->validate($this->rules());
        $validated['created_by_id'] = $this->currentAdminAndDriverId($request);

        $this->adminAndDriverService->createAdminAndDriver($validated);

        return redirect()
            ->route('admin-and-drivers.index')
            ->with('success', 'Admin / driver created successfully.');
    }

    public function show(AdminAndDriver $adminAndDriver): View
    {
        $adminAndDriver->load(['createdBy', 'updatedBy']);

        return view('admin_and_drivers.show', [
            'adminAndDriver' => $adminAndDriver,
        ]);
    }

    public function edit(AdminAndDriver $adminAndDriver): View
    {
        return view('admin_and_drivers.edit', [
            'adminAndDriver' => $adminAndDriver,
            'userRoles' => AdminAndDriverService::USER_ROLES,
        ]);
    }

    public function update(Request $request, AdminAndDriver $adminAndDriver): RedirectResponse
    {
        $this->normalizeUsername($request);

        $validated = $request->validate($this->rules($adminAndDriver->id, false));
        $validated['updated_by_id'] = $this->currentAdminAndDriverId($request);

        $this->adminAndDriverService->updateAdminAndDriver($adminAndDriver, $validated);

        return redirect()
            ->route('admin-and-drivers.index')
            ->with('success', 'Admin / driver updated successfully.');
    }

    public function destroy(AdminAndDriver $adminAndDriver): RedirectResponse
    {
        $this->adminAndDriverService->deleteAdminAndDriver($adminAndDriver);

        return redirect()
            ->route('admin-and-drivers.index')
            ->with('success', 'Admin / driver deleted successfully.');
    }

    private function rules(?int $adminAndDriverId = null, bool $isCreate = true): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'email_id' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('tenant.admin_and_drivers', 'email_id')->ignore($adminAndDriverId),
            ],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tenant.admin_and_drivers', 'username')->ignore($adminAndDriverId),
            ],
            'password' => [$isCreate ? 'required' : 'nullable', 'string', 'min:6', 'max:255'],
            'user_role' => ['required', 'string', Rule::in(AdminAndDriverService::USER_ROLES)],
            'license_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('tenant.admin_and_drivers', 'license_number')->ignore($adminAndDriverId),
            ],
            'license_expiry_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    private function normalizeUsername(Request $request): void
    {
        $request->merge([
            'username' => trim((string) $request->input('username')),
        ]);
    }
}
