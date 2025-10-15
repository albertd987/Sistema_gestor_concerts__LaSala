<!DOCTYPE html>
<html lang="ca" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sol·licitar Slot - LaSala</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-900 text-gray-100">
    {{-- Navbar --}}
    <nav class="bg-gray-800 border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                {{-- Logo --}}
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-purple-500">
                        LaSala
                    </a>
                </div>

                {{-- Navegació Artista --}}
                <div class="flex items-center space-x-4">
                    <a href="/artista/reservar" 
                       class="bg-gray-700 text-white px-3 py-2 rounded-md text-sm font-medium">
                        Sol·licitar Slot
                    </a>
                    <a href="/artista/reserves" 
                       class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                        Les Meves Reserves
                    </a>
                    
                    {{-- User info + Logout --}}
                    <div class="flex items-center gap-3 ml-4 pl-4 border-l border-gray-700">
                        <span class="text-sm text-gray-400">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm text-red-400 hover:text-red-300">
                                Sortir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- Contingut: Component Livewire --}}
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <livewire:artista.create-reservation />
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-800 border-t border-gray-700 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-gray-500 text-sm">
                © {{ date('Y') }} LaSala. Tots els drets reservats.
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>