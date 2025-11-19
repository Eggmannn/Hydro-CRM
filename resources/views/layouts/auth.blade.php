<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login')</title>

    {{-- ✅ Include Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: #020a1bff;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen">

    @yield('content')

</body>
</html>
