<?php

namespace App\Http\Controllers;

use App\DTOs\TenantData;
use App\Http\Requests\Tenant\CreateTenantRequest;
use App\Http\Requests\Tenant\UpdateTenantRequest;
use App\Models\Tenant;
use App\Services\Tenant\TenantService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function __construct(
        private readonly TenantService $tenantService
    ) {}

    public function index(): View
    {
        $tenants = auth()->user()->tenants;
        return view('tenants.index', compact('tenants'));
    }

    public function create(): View
    {
        return view('tenants.create');
    }

    public function store(CreateTenantRequest $request): RedirectResponse
    {
        $data = TenantData::fromArray($request->validated());
        $tenant = $this->tenantService->createTenant($data, auth()->id());

        return redirect()
            ->route('dashboard')
            ->with('success', "Organization '{$tenant->name}' created successfully!");
    }

    public function show(Tenant $tenant): View
    {
        $members = $tenant->users;
        return view('tenants.show', compact('tenant', 'members'));
    }

    public function edit(Tenant $tenant): View
    {
        return view('tenants.edit', compact('tenant'));
    }

    public function update(UpdateTenantRequest $request, Tenant $tenant): RedirectResponse
    {
        $data = TenantData::fromArray($request->validated());
        $this->tenantService->updateTenant($tenant->id, $data);

        return redirect()
            ->route('tenants.show', $tenant)
            ->with('success', 'Organization updated successfully.');
    }

    public function switchTenant(Tenant $tenant): RedirectResponse
    {
        auth()->user()->switchTenant($tenant);

        return redirect()
            ->route('dashboard')
            ->with('success', "Switched to {$tenant->name}.");
    }
}