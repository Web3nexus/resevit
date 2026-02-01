<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $template->name }} - Coming Soon</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 flex items-center justify-center h-screen">
    <div class="text-center p-8 bg-white shadow-lg rounded-xl max-w-md">
        <div class="w-16 h-16 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl">🚧</div>
        <h1 class="text-2xl font-bold mb-2">{{ $template->name }}</h1>
        <p class="text-gray-500 mb-6">This template design is under construction.</p>
        <div class="text-left bg-gray-100 p-4 rounded text-sm font-mono overflow-auto max-h-40">
            <strong>Default Content:</strong><br>
            <pre>{{ json_encode($template->default_content, JSON_PRETTY_PRINT) }}</pre>
        </div>
    </div>
</body>

</html>