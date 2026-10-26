<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ISMS Tool') }} — Authentication</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .grid-pattern {
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }
    </style>
</head>

<body class="antialiased">

    <div class="min-h-screen flex">

        {{-- LEFT SIDE: Branding (Hidden on Mobile) --}}
        <div class="hidden lg:flex lg:w-[55%] relative bg-slate-900 overflow-hidden">

            {{-- Grid Pattern Overlay --}}
            <div class="absolute inset-0 grid-pattern"></div>

            {{-- Gradient Glow --}}
            <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-600 rounded-full opacity-30 blur-3xl"></div>
            <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-indigo-500 rounded-full opacity-20 blur-3xl"></div>

            <div class="relative z-10 flex flex-col justify-between p-12 w-full">

                {{-- Logo --}}
                <a href="/" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="font-bold text-xl text-white">{{ config('app.name', 'ISMS Tool') }}</span>
                </a>

                {{-- Main Content --}}
                <div class="max-w-md">
                    <h1 class="text-4xl font-extrabold text-white leading-tight mb-6">
                        ISO 27001 compliance,<br>
                        <span class="text-indigo-400">without the spreadsheet chaos.</span>
                    </h1>
                    <p class="text-lg text-slate-300 leading-relaxed mb-8">
                        Assess 115 ISO controls, manage risks with a 5×5 heatmap, and prove compliance to auditors — all
                        in one platform.
                    </p>

                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <div
                                class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-600/20 flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-white">Assessment Engine</p>
                                <p class="text-sm text-slate-400">115 controls, maturity scoring, gap analysis</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div
                                class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-600/20 flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-white">Risk Management</p>
                                <p class="text-sm text-slate-400">5×5 heatmap, treatment plans, notifications</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div
                                class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-600/20 flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-white">Audit-Ready Reports</p>
                                <p class="text-sm text-slate-400">SoA, executive summaries, PDF/Excel exports</p>
                            </div>
                        </li>
                    </ul>
                </div>

                {{-- Footer --}}
                <div>
                    <p class="text-sm text-slate-400">© {{ date('Y') }} {{ config('app.name', 'ISMS Tool') }}. All
                        rights reserved.</p>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDE: Form --}}
        <div class="w-full lg:w-[45%] flex flex-col justify-center px-4 sm:px-8 md:px-12 lg:px-16 bg-slate-50">

            {{-- Mobile Logo --}}
            <div class="lg:hidden mb-8 flex justify-center">
                <a href="/" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="font-bold text-xl text-slate-900">{{ config('app.name', 'ISMS Tool') }}</span>
                </a>
            </div>

            <div class="w-full max-w-md mx-auto">
                {{ $slot }}
            </div>

            {{-- Mobile Footer --}}
            <div class="lg:hidden mt-8 text-center">
                <p class="text-xs text-slate-500">© {{ date('Y') }} {{ config('app.name', 'ISMS Tool') }}</p>
            </div>
        </div>

    </div>

</body>

</html>