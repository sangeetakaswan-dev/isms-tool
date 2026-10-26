@extends('layouts.app')
@section('header')
    <h2 class="font-semibold text-xl text-slate-900">Pending Invitations</h2>
@endsection
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase">
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Role</th>
                    <th class="px-6 py-4">Expires</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($invitations as $invite)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm font-medium">{{ $invite->email }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ ucfirst($invite->role) }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $invite->expires_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <form method="POST" action="{{ route('invitations.destroy', [$tenant, $invite]) }}">
                                @csrf @method('DELETE')
                                <button class="text-xs text-red-600 hover:underline">Revoke</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-slate-500">No pending invitations.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection