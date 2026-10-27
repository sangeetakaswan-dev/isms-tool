<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Page Expired | ISMS Audit</title>
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
        <div class="w-24 h-24 bg-orange-50 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-sm">
            <svg class="w-12 h-12 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h1 class="text-8xl font-black text-slate-900 tracking-tighter mb-4">419</h1>
        <h2 class="text-2xl font-bold text-slate-900 mb-2">Page Expired</h2>
        <p class="text-slate-500 mb-8">Security reasons ki wajah se tera session expire ho gaya hai. Kripya page refresh
            karke dubara try kar.</p>
        <a href="javascript:history.back()"
            class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition shadow-sm">
            Go Back & Retry
        </a>
    </div>
</body>

</html>