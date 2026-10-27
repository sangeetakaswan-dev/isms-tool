<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 1. Create user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 2. Auto-create personal tenant
        $tenant = Tenant::create([
            'name'          => $user->name . "'s Organization",
            'slug'          => Str::slug($user->name) . '-' . Str::random(6),
            'contact_email' => $user->email,
            'industry'      => 'Technology',
            'size'          => '1-10',
            'country'       => 'India',
        ]);

        // 3. Attach user as owner
        $user->tenants()->attach($tenant->id, ['role' => 'owner']);

        // 4. Set as current tenant
        $user->update(['current_tenant_id' => $tenant->id]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}