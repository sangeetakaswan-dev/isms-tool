<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invitation\CreateInvitationRequest;
use App\Models\Tenant;
use App\Services\Tenant\InvitationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InvitationController extends Controller
{
    public function __construct(
        private readonly InvitationService $invitationService
    ) {}

    public function index(Tenant $tenant): View
    {
        $invitations = $tenant->invitations()->paginate(15);
        return view('invitations.index', compact('tenant', 'invitations'));
    }

    public function store(CreateInvitationRequest $request, Tenant $tenant): RedirectResponse
    {
        $this->invitationService->createInvitation(
            $tenant,
            $request->email,
            $request->role,
            auth()->user()
        );

        return redirect()
            ->route('invitations.index', $tenant)
            ->with('success', "Invitation sent to {$request->email}.");
    }

    public function destroy(Tenant $tenant, $invitationId): RedirectResponse
    {
        $invitation = $tenant->invitations()->findOrFail($invitationId);
        $this->invitationService->revokeInvitation($invitation);

        return redirect()
            ->route('invitations.index', $tenant)
            ->with('success', 'Invitation revoked.');
    }
}