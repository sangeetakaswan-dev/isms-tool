<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="ISO/IEC 27001:2022 compliance management platform. Assess, manage risks, and prove compliance — without the spreadsheet chaos.">
    <title>{{ config('app.name', 'ISMS Tool') }} — ISO 27001 Compliance Made Simple</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=jetbrains-mono:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        .grid-pattern {
            background-image:
                linear-gradient(to right, rgba(15, 23, 42, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(15, 23, 42, 0.04) 1px, transparent 1px);
            background-size: 32px 32px;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased">

    {{-- NAV --}}
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <span class="font-bold text-lg">{{ config('app.name', 'ISMS Tool') }}</span>
            </a>
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-sm font-medium text-slate-600 hover:text-slate-900">Features</a>
                <a href="#how-it-works" class="text-sm font-medium text-slate-600 hover:text-slate-900">How it Works</a>
            </div>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                        Go to Dashboard
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="text-sm font-medium text-slate-700 hover:text-slate-900 px-3 py-2">Sign in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">Get
                            Started</a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    {{-- HERO --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 grid-pattern"></div>
        <div class="relative max-w-7xl mx-auto px-6 py-24 md:py-32">
            <div class="max-w-3xl">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 border border-indigo-200 rounded-full text-xs font-medium text-indigo-700 mb-6">
                    <span class="w-1.5 h-1.5 bg-indigo-600 rounded-full"></span>
                    ISO/IEC 27001:2022 ready
                </div>
                <h1 class="text-4xl md:text-6xl font-extrabold text-slate-900 leading-[1.1] tracking-tight">
                    ISO 27001 compliance,<br>
                    <span class="text-indigo-600">without the spreadsheet chaos.</span>
                </h1>
                <p class="mt-6 text-lg md:text-xl text-slate-600 leading-relaxed max-w-2xl">
                    Assess 115 ISO controls, manage risks with a 5×5 heatmap, generate Statement of Applicability, and
                    prove compliance to auditors — all in one platform.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-base font-semibold rounded-lg transition shadow-lg shadow-indigo-600/20">
                            Open Dashboard
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-base font-semibold rounded-lg transition shadow-lg shadow-indigo-600/20">
                            Start Free Trial
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                        <a href="#how-it-works"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 border border-slate-300 hover:bg-white text-slate-700 text-base font-semibold rounded-lg transition">See
                            How It Works</a>
                    @endauth
                </div>
                @guest
                    <div class="mt-8 text-sm text-slate-500">
                        Try the demo: <code
                            class="font-mono bg-slate-100 px-2 py-0.5 rounded text-slate-700">owner@demo.com</code> / <code
                            class="font-mono bg-slate-100 px-2 py-0.5 rounded text-slate-700">password</code>
                    </div>
                @endguest
            </div>
        </div>
    </section>

    {{-- TRUST BAR --}}
    <section class="border-y border-slate-200 bg-white">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <p class="text-center text-xs font-medium text-slate-500 uppercase tracking-wider mb-6">Built for compliance
                teams</p>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 items-center opacity-60">
                @foreach(['Acme Corp', 'TechFlow', 'SecureBank', 'DataShield', 'CloudNine'] as $logo)
                    <div class="text-center text-slate-400 font-bold text-lg">{{ $logo }}</div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FEATURES BENTO --}}
    <section id="features" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="max-w-2xl mb-16">
                <span class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Features</span>
                <h2 class="mt-3 text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">Everything you need to
                    pass ISO 27001 audits</h2>
                <p class="mt-4 text-lg text-slate-600">From assessment to certification — a complete GRC toolkit for
                    compliance teams.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2 bg-white border border-slate-200 rounded-2xl p-8 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Assessment Engine</h3>
                    <p class="mt-2 text-slate-600">115 ISO 27001:2022 controls across 11 domains. Maturity scoring
                        (0-5), weighted compliance calculation, evidence notes, and gap analysis — all in real time.</p>
                    <div class="mt-6 flex flex-wrap gap-2">
                        <span class="px-2.5 py-1 bg-slate-100 text-xs font-medium text-slate-700 rounded-md">115
                            controls</span>
                        <span class="px-2.5 py-1 bg-slate-100 text-xs font-medium text-slate-700 rounded-md">Maturity
                            0-5</span>
                        <span class="px-2.5 py-1 bg-slate-100 text-xs font-medium text-slate-700 rounded-md">Gap
                            analysis</span>
                    </div>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-8 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Risk Heatmap</h3>
                    <p class="mt-2 text-slate-600">5×5 likelihood × impact matrix with clickable drilldown. Inherent vs
                        residual scoring, 4 treatment strategies, and auto-transition workflow.</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-8 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Statement of Applicability</h3>
                    <p class="mt-2 text-slate-600">Generate SoA from assessments with applicability justification,
                        approval workflow, and version comparison.</p>
                </div>
                <div class="md:col-span-2 bg-white border border-slate-200 rounded-2xl p-8 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Executive Reports</h3>
                    <p class="mt-2 text-slate-600">6 report types — Executive Summary, Gap Analysis, Risk Register, SoA
                        Status, Document Compliance, Asset Inventory. Export as PDF, Excel, or CSV. Audit trail of every
                        export.</p>
                    <div class="mt-6 flex flex-wrap gap-2">
                        <span class="px-2.5 py-1 bg-slate-100 text-xs font-medium text-slate-700 rounded-md">6 report
                            types</span>
                        <span class="px-2.5 py-1 bg-slate-100 text-xs font-medium text-slate-700 rounded-md">PDF / Excel
                            / CSV</span>
                        <span class="px-2.5 py-1 bg-slate-100 text-xs font-medium text-slate-700 rounded-md">Export
                            history</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- HOW IT WORKS --}}
    <section id="how-it-works" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="max-w-2xl mb-16">
                <span class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">How it works</span>
                <h2 class="mt-3 text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">Three steps to audit
                    readiness</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach([
                        ['01', 'Assess', 'Run a comprehensive assessment of 115 ISO 27001:2022 controls. Score maturity, note evidence, and identify gaps automatically.'],
                        ['02', 'Treat', 'Log risks against assets, score them on a 5×5 matrix, and assign treatment plans with owners and due dates.'],
                        ['03', 'Prove', 'Generate Statement of Applicability, executive reports, and audit-ready evidence packages. Export everything in one click.'],
                    ] as $step)
                    <div>
                        <div class="text-6xl font-bold text-indigo-100 font-mono">{{ $step[0] }}</div>
                        <h3 class="mt-4 text-2xl font-bold text-slate-900">{{ $step[1] }}</h3>
                        <p class="mt-3 text-slate-600">{{ $step[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FINAL CTA --}}
    <section class="py-24 bg-slate-900">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-3xl md:text-5xl font-bold text-white tracking-tight">Ready to pass your next audit?</h2>
            <p class="mt-6 text-lg text-slate-300">Start with a free trial. No credit card required.</p>
            <div class="mt-10 flex flex-col sm:flex-row gap-3 justify-center">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-slate-900 text-base font-semibold rounded-lg hover:bg-slate-100 transition">Open
                        Dashboard</a>
                @else
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-indigo-600 text-white text-base font-semibold rounded-lg hover:bg-indigo-700 transition">Start
                        Free Trial</a>
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center justify-center gap-2 px-8 py-4 border border-slate-700 text-white text-base font-semibold rounded-lg hover:bg-slate-800 transition">Sign
                        In</a>
                @endauth
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-slate-900 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <h4 class="text-white font-semibold mb-4">Product</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="#features" class="hover:text-white">Features</a></li>
                        <li><a href="#how-it-works" class="hover:text-white">How it Works</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Company</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-white">About</a></li>
                        <li><a href="#" class="hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Resources</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-white">Documentation</a></li>
                        <li><a href="#" class="hover:text-white">ISO 27001 Guide</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-white">Privacy</a></li>
                        <li><a href="#" class="hover:text-white">Terms</a></li>
                    </ul>
                </div>
            </div>
            <div
                class="mt-12 pt-8 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-slate-400">© {{ date('Y') }} {{ config('app.name', 'ISMS Tool') }}. All rights
                    reserved.</p>
                <p class="text-sm text-slate-500 font-mono">v2.0</p>
            </div>
        </div>
    </footer>

</body>

</html>