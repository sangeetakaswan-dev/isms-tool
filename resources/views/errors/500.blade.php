<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error | ISMS Audit</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full text-center">
        <div class="w-24 h-24 bg-amber-50 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-sm">
            <svg class="w-12 h-12 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h1 class="text-8xl font-black text-slate-900 tracking-tighter mb-4">500</h1>
        <h2 class="text-2xl font-bold text-slate-900 mb-2">Internal Server Error</h2>
        <p class="text-slate-500 mb-8">Oops! Server me kuch gadbad ho gayi hai. Hamari team ko iski jankari chali gayi
            hai. Thodi der baad try karna.</p>
        <a href="{{ url('/') }}"
            class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg transition shadow-sm">
            Refresh Page
        </a>
    </div>
</body>

</html>