<!DOCTYPE html>
<html lang="ca" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'LaSala - Gestió de Reserves' }}</title>
    
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

                {{-- Navegació --}}
                <div class="flex items-center space-x-4">
                    @auth
                        {{-- Admin Links --}}
                        @if(auth()->user()->isAdmin())
                            <a href="/admin/dashboard" 
                               class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                Dashboard
                            </a>
                            <a href="/admin/slots" 
                               class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                Slots
                            </a>
                            <a href="/admin/reserves" 
                               class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                Reserves
                            </a>
                        @endif

                        {{-- Artist Links --}}
                        @if(auth()->user()->isArtista())
                            <a href="/artista/dashboard" 
                               class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                Les Meves Reserves
                            </a>
                            <a href="/artista/slots" 
                               class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                Reservar Slot
                            </a>
                        @endif

                        {{-- User Info --}}
                        <div class="flex items-center space-x-3 ml-4 pl-4 border-l border-gray-700">
                            <span class="text-sm text-gray-400">{{ auth()->user()->name }}</span>
                            <span class="px-2 py-1 text-xs rounded-full {{ auth()->user()->rol->ColorUI() === 'red' ? 'bg-red-500/20 text-red-400' : (auth()->user()->rol->ColorUI() === 'purple' ? 'bg-purple-500/20 text-purple-400' : 'bg-gray-500/20 text-gray-400') }}">
                                {{ auth()->user()->rol->label() }}
                            </span>
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit" class="text-gray-400 hover:text-white text-sm">
                                    Sortir
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="/login" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                            Login
                        </a>
                        <a href="/register" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Registrar-se
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Contingut Principal --}}
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-800 border-t border-gray-700 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-bold text-white mb-2">LaSala</h3>
                    <p class="text-gray-400 text-sm">
                        Discoteca de música en viu a Igualada
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white mb-2">Contacte</h3>
                    <p class="text-gray-400 text-sm">
                        C/ Example, 123<br>
                        08700 Igualada<br>
                        info@lasala.cat
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white mb-2">Horari</h3>
                    <p class="text-gray-400 text-sm">
                        Dijous a Dissabte<br>
                        22:00 - 03:00
                    </p>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-500 text-sm">
                © {{ date('Y') }} LaSala. Tots els drets reservats.
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>