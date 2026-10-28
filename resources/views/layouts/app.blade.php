<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ISMS Audit') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased" x-data="{ sidebarOpen: true, mobileOpen: false }">

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen" x-cloak @click="mobileOpen = false"
        class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden transition-opacity"></div>

    <!-- SIDEBAR -->
    <aside
        class="fixed inset-y-0 left-0 z-50 bg-white border-r border-slate-200 transition-all duration-300 ease-in-out flex flex-col -translate-x-full lg:translate-x-0"
        :class="mobileOpen ? '!translate-x-0 w-64' : (sidebarOpen ? 'lg:w-64' : 'lg:w-20')">
        
        <!-- 🟢 LOGO HEADER (Sahi Jagah) -->
        <div class="h-16 flex items-center justify-between px-4 border-b border-slate-200 flex-shrink-0 overflow-hidden">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-xl shadow-sm border border-slate-200 flex items-center justify-center flex-shrink-0 p-1.5">
                    <img src="{{ asset('images/logo-icon.png') }}" alt="ISMS Logo" class="w-full h-full object-contain">
                </div>
                <div x-show="sidebarOpen || mobileOpen" class="flex flex-col text-left overflow-hidden whitespace-nowrap">
                    <span class="text-lg font-extrabold text-slate-900 leading-tight">
                        ISMS <span class="text-indigo-600">Audit</span>
                    </span>
                    <span class="text-[9px] font-semibold text-slate-500 uppercase tracking-widest">
                        ISO 27001 Compliance
                    </span>
                </div>
            </a>
            <!-- Close Button (Mobile Only) -->
            <button @click="mobileOpen = false" class="lg:hidden p-1.5 text-slate-500 hover:bg-slate-100 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Nav Links -->
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-6">
            <div>
                <p x-show="sidebarOpen || mobileOpen" class="px-2 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Governance Core</p>
                <ul class="space-y-1">
                    <li>
                        <!-- 🟢 DASHBOARD LINK (Wapas Normal) -->
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-3 px-2 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span x-show="sidebarOpen || mobileOpen" class="whitespace-nowrap">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('assessments.index') }}"
                            class="flex items-center gap-3 px-2 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('assessments.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span x-show="sidebarOpen || mobileOpen" class="whitespace-nowrap">Assessments</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('documents.index') }}"
                            class="flex items-center gap-3 px-2 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('documents.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <span x-show="sidebarOpen || mobileOpen" class="whitespace-nowrap">Documents</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('soa.direct') }}"
                            class="flex items-center gap-3 px-2 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('soa.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <span x-show="sidebarOpen || mobileOpen" class="whitespace-nowrap">SoA</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <p x-show="sidebarOpen || mobileOpen" class="px-2 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Audit & Operations</p>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('risks.index') }}"
                            class="flex items-center gap-3 px-2 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('risks.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span x-show="sidebarOpen || mobileOpen" class="whitespace-nowrap">Risk Register</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('assets.index') }}"
                            class="flex items-center gap-3 px-2 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('assets.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                            </svg>
                            <span x-show="sidebarOpen || mobileOpen" class="whitespace-nowrap">Assets</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.index') }}"
                            class="flex items-center gap-3 px-2 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('reports.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span x-show="sidebarOpen || mobileOpen" class="whitespace-nowrap">Reports</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <p x-show="sidebarOpen || mobileOpen" class="px-2 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">System</p>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('tenants.index') }}"
                            class="flex items-center gap-3 px-2 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('tenants.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span x-show="sidebarOpen || mobileOpen" class="whitespace-nowrap">Organization</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('notifications.index') }}"
                            class="flex items-center gap-3 px-2 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('notifications.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span x-show="sidebarOpen || mobileOpen" class="whitespace-nowrap">Notifications</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Collapse Button (Desktop Only) -->
        <div class="border-t border-slate-200 p-3 flex-shrink-0 mt-auto hidden lg:block">
            <button @click="sidebarOpen = !sidebarOpen"
                class="w-full flex items-center justify-center lg:justify-start gap-3 px-2 py-2 rounded-lg text-sm font-medium text-slate-500 hover:bg-slate-100 hover:text-slate-900 transition">
                <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-300" :class="sidebarOpen ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Collapse</span>
            </button>
        </div>
    </aside>

    <!-- MAIN CONTENT (Topbar + Page Content) -->
    <div class="flex-1 flex flex-col min-h-screen transition-all duration-300 w-full" :class="sidebarOpen ? 'lg:pl-64' : 'lg:pl-20'">
        
        <!-- TOP BAR -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-6 sticky top-0 z-30 flex-shrink-0">
            <div class="flex items-center gap-3">
                <button @click="mobileOpen = true" class="lg:hidden p-2 -ml-2 text-slate-500 hover:bg-slate-100 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div>
                    {{ $header ?? '' }}
                </div>
            </div>
            
            <div class="flex items-center gap-2 md:gap-4">
                @auth
                    <a href="{{ route('notifications.index') }}" class="relative p-2 rounded-lg hover:bg-slate-100 transition">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                        @if($unreadCount > 0)
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        @endif
                    </a>
                    
                    <!-- User Menu Dropdown -->
                    <div class="relative" x-data="{ userMenuOpen: false }" @click.outside="userMenuOpen = false">
                        <button @click="userMenuOpen = !userMenuOpen" class="flex items-center gap-2 p-1 rounded-lg hover:bg-slate-100 transition">
                            <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-semibold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="hidden lg:inline text-sm font-medium text-slate-700">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-slate-500 hidden lg:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <!-- Dropdown content -->
                        <div x-show="userMenuOpen" x-cloak x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-xl border border-slate-200 shadow-lg z-50 overflow-hidden">
                            <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
                                <p class="text-sm font-semibold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    Profile Settings
                                </a>
                                <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                    Notifications
                                </a>
                                <a href="{{ route('tenants.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                    Organization
                                </a>
                            </div>
                            <div class="border-t border-slate-100 py-1">
                                <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
        Logout
    </button>
</form>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-4 md:p-6 overflow-x-hidden">
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endforelse
        </main>
    </div>

    @stack('scripts')
</body>
</html>