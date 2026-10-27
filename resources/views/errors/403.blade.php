<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Denied | ISMS Audit</title>
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
        <div class="w-24 h-24 bg-red-50 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-sm">
            <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <h1 class="text-8xl font-black text-slate-900 tracking-tighter mb-4">403</h1>
        <h2 class="text-2xl font-bold text-slate-900 mb-2">Access Denied</h2>
        <p class="text-slate-500 mb-8">Sorry! Tere paas is page ya action ko access karne ki permission nahi hai.
            (Role-based restriction).</p>
        <a href="{{ route('dashboard') }}"
            class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition shadow-sm">
            Go to Dashboard
        </a>
    </div>
</body>

</html>