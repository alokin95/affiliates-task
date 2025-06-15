<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title @yield('title', 'Application')>Affiliates</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 text-gray-900">
<header class="bg-white shadow">
    <div class="container mx-auto px-4 py-4">
        <a href="{{ route('affiliates.index') }}" class="text-xl font-semibold">Affiliate Finder</a>
    </div>
</header>

<main class="container mx-auto px-4 py-8">
    @yield('content')
</main>

<footer class="border-t bg-white mt-8 py-4 text-center text-sm text-gray-600">
    &copy; {{ date('Y') }} Affiliate finder
</footer>
</body>
</html>
