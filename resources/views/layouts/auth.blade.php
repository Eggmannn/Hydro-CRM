<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login')</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Allow pages to inject extra head content --}}
    @stack('head')
</head>

<body class="@yield('bodyClass', 'bg-gray-100 dark:bg-gray-950') min-h-screen">

    @yield('content')

</body>
</html>
