@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-slate-900 leading-tight">Notifications</h2>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Notifications</h1>
            <p class="text-sm text-slate-500 mt-1">
                @php $unreadCount = $notifications->whereNull('read_at')->count(); @endphp
                @if($unreadCount > 0)
                    You have <span class="font-semibold text-indigo-600">{{ $unreadCount }} unread</span> {{ Str::plural('notification', $unreadCount) }}.
                @else
                    You're all caught up.
                @endif
            </p>
        </div>
        @if($unreadCount > 0)
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Mark all as read
                </button>
            </form>
        @endif
    </div>

    {{-- Notifications List --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="divide-y divide-slate-100">
            @forelse($notifications as $notification)
                @php
                    $type = $notification->data['type'] ?? 'generic';
                    $iconConfig = match(true) {
                        str_contains($type, 'risk') => [
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
                            'bg' => 'bg-red-50',
                            'text' => 'text-red-600',
                        ],
                        str_contains($type, 'treatment') => [
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
                            'bg' => 'bg-indigo-50',
                            'text' => 'text-indigo-600',
                        ],
                        str_contains($type, 'document') => [
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>',
                            'bg' => 'bg-amber-50',
                            'text' => 'text-amber-600',
                        ],
                        default => [
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>',
                            'bg' => 'bg-slate-100',
                            'text' => 'text-slate-600',
                        ],
                    };
                    $isUnread = is_null($notification->read_at);
                @endphp
                <div class="p-4 sm:p-6 hover:bg-slate-50 transition-colors {{ $isUnread ? 'bg-indigo-50/30' : '' }}">
                    <div class="flex items-start gap-4">
                        {{-- Icon --}}
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full {{ $iconConfig['bg'] }} {{ $iconConfig['text'] }} flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $iconConfig['icon'] !!}</svg>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-slate-900 {{ $isUnread ? '' : 'font-medium' }}">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                        @if($isUnread)
                                            <span class="inline-block ml-2 w-2 h-2 bg-indigo-600 rounded-full align-middle"></span>
                                        @endif
                                    </p>
                                    @if(!empty($notification->data['message']))
                                        <p class="text-sm text-slate-600 mt-1">{{ $notification->data['message'] }}</p>
                                    @endif
                                    <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    @if(!empty($notification->data['url']))
                                        <a href="{{ $notification->data['url'] }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">
                                            View →
                                        </a>
                                    @endif
                                    @if($isUnread)
                                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                            @csrf
                                            <button type="submit" class="text-xs text-slate-500 hover:text-slate-700" title="Mark as read">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-slate-900">All caught up!</h3>
                    <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                        You don't have any notifications right now. We'll let you know when something needs your attention.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($notifications->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection