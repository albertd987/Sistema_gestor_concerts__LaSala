<!DOCTYPE html>
<html lang="ca" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LaSala - Concerts i Esdeveniments')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-black text-white font-sans antialiased">
    {{-- Navbar --}}
    <x-public-navbar />

    {{-- Contingut principal --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <x-public-footer />

    @livewireScripts
</body>

</html>