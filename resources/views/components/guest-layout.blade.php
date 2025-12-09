<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
</body>
<body class="font-sans antialiased" style="background: linear-gradient(180deg, var(--color-offwhite) 0%, #f5f7fb 100%);">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
        <div class="w-full sm:max-w-md">
            <div class="flex justify-center mb-6">
                <h1 class="text-3xl font-bold" style="color:var(--color-navy);">{{ config('app.name', 'Resevit') }}</h1>
            </div>
            <div class="card overflow-hidden">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
