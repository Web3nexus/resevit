<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $tenant->name }} - Coming Soon</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-offwhite font-sans antialiased h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full text-center">
        <div class="mb-12">
            <h1 class="text-4xl font-black text-brand-primary uppercase tracking-tighter italic">
                {{ $tenant->name }}
            </h1>
        </div>

        <div class="bg-white p-10 rounded-[2.5rem] shadow-2xl border border-slate-100">
            <div
                class="w-20 h-20 bg-brand-accent/20 text-brand-accent rounded-full flex items-center justify-center mx-auto mb-8">
                <i class="fas fa-hammer text-4xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Under Construction</h2>
            <p class="text-slate-500 leading-relaxed mb-10">
                We are currently crafting our new digital experience. Please check back shortly or visit us in person!
            </p>

            <div class="pt-6 border-t border-slate-100">
                <p class="text-xs text-slate-400 uppercase tracking-widest font-bold">Powered by Resevit</p>
            </div>
        </div>
    </div>
</body>

</html>