<?php

namespace App\Services\Tenant;

use App\Models\Invitation;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class InvitationService
{
    public function createInvitation(Tenant $tenant, string $email, string $role, User $inviter): Invitation
    {
        $invitation = Invitation::create([
            'tenant_id' => $tenant->id,
            'email' => $email,
            'token' => Str::random(64),
            'role' => $role,
            'expires_at' => Carbon::now()->addDays(7),
            'invited_by' => $inviter->id,
        ]);

        // Send email (if configured)
        // Mail::to($email)->send(new InvitationMail($invitation));

        return $invitation;
    }

    public function acceptInvitation(string $token, User $user): bool
    {
        $invitation = Invitation::where('token', $token)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->firstOrFail();

        // Add user to tenant
        $invitation->tenant->users()->attach($user->id, [
            'role' => $invitation->role,
            'is_default' => false,
        ]);

        $invitation->accepted_at = now();
        $invitation->save();

        return true;
    }

    public function revokeInvitation(Invitation $invitation): void
    {
        $invitation->delete();
    }

    public function resendInvitation(Invitation $invitation): void
    {
        $invitation->expires_at = Carbon::now()->addDays(7);
        $invitation->save();

        // Resend email
        // Mail::to($invitation->email)->send(new InvitationMail($invitation));
    }
}