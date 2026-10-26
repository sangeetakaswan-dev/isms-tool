@extends('layouts.app')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('tenants.index') }}" class="text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">Organization Details</h2>
    </div>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ $tenant->name }}</h1>
                    <p class="text-sm text-slate-500">{{ $tenant->industry ?? 'Organization' }} ·
                        {{ $tenant->contact_email ?? '' }}
                    </p>
                </div>
            </div>
            @if(auth()->user()->current_tenant_id !== $tenant->id)
                <form method="POST" action="{{ route('tenants.switch', $tenant) }}">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition shadow-sm">
                        Switch to this Organization
                    </button>
                </form>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT: Members --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                        <h3 class="text-base font-semibold text-slate-900">Team Members</h3>
                        <span
                            class="text-xs font-medium text-slate-500 bg-white border border-slate-200 px-2 py-1 rounded-md">{{ $tenant->users->count() }}
                            Members</span>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @foreach($tenant->users as $user)
                            <div class="p-4 sm:px-6 flex items-center justify-between hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div>
                                    @php
                                        $role = $user->pivot->role ?? 'member';
                                        $roleColors = [
                                            'owner' => 'bg-purple-50 text-purple-700 ring-purple-600/20',
                                            'admin' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
                                            'assessor' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                            'auditor' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                            'viewer' => 'bg-slate-100 text-slate-700 ring-slate-600/20',
                                        ];
                                        $roleClass = $roleColors[$role] ?? 'bg-slate-100 text-slate-700 ring-slate-600/20';
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $roleClass }}">
                                        {{ ucfirst($role) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- RIGHT: Invite & Info --}}
            <div class="space-y-6">

                {{-- Invite Form --}}
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-900 mb-4">Invite a Member</h3>
                    <form method="POST" action="{{ route('invitations.store', $tenant) }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Email address</label>
                            <input type="email" name="email" required placeholder="colleague@company.com"
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Role</label>
                            <select name="role" required
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="viewer">Viewer</option>
                                <option value="assessor">Assessor</option>
                                <option value="auditor">Auditor</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <button type="submit"
                            class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                            Send Invitation
                        </button>
                    </form>
                </div>

                {{-- Pending Invitations List --}}
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm mt-6">
                    <h3 class="text-sm font-semibold text-slate-900 mb-4 border-b border-slate-100 pb-3">Pending Invitations
                    </h3>
                    @php
                        $pendingInvites = \App\Models\Invitation::where('tenant_id', $tenant->id)->whereNull('accepted_at')->get();
                    @endphp
                    @forelse($pendingInvites as $invite)
                        <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                            <div class="text-sm text-slate-700">{{ $invite->email }}</div>
                            <span
                                class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded">{{ ucfirst($invite->role) }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500">No pending invitations.</p>
                    @endforelse
                </div>

                {{-- Organization Info --}}
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-900 mb-4 border-b border-slate-100 pb-3">Details</h3>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-xs text-slate-500 mb-1">Contact Email</dt>
                            <dd class="text-sm font-medium text-slate-900">{{ $tenant->contact_email ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500 mb-1">Phone</dt>
                            <dd class="text-sm font-medium text-slate-900">{{ $tenant->contact_phone ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500 mb-1">Location</dt>
                            <dd class="text-sm font-medium text-slate-900">{{ $tenant->city ?? '—' }},
                                {{ $tenant->country ?? '' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500 mb-1">Created</dt>
                            <dd class="text-sm font-medium text-slate-900">{{ $tenant->created_at->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </div>

            </div>
        </div>
    </div>
@endsection