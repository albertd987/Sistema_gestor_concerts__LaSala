{{-- Navbar per a visitants públics --}}
<nav class="fixed top-0 w-full bg-black/90 backdrop-blur-sm border-b border-gray-800 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            
            {{-- Logo --}}
            <a href="/agenda" class="flex items-center">
                <span class="text-3xl font-black tracking-tighter text-white">
                    LASALA
                </span>
            </a>

            {{-- Navegació desktop --}}
            <div class="hidden md:flex items-center space-x-8">
                <a href="/agenda" 
                   class="text-gray-300 hover:text-white font-semibold tracking-wide transition-colors uppercase text-sm">
                    Agenda
                </a>

                @auth
                    {{-- Si està autenticat, redirigir al seu dashboard --}}
                    @if(auth()->user()->isAdmin())
                        <a href="/admin/reserves" 
                           class="text-gray-300 hover:text-white font-semibold tracking-wide transition-colors uppercase text-sm">
                            Admin
                        </a>
                    @elseif(auth()->user()->isArtista())
                        <a href="/artista/reservar" 
                           class="text-gray-300 hover:text-white font-semibold tracking-wide transition-colors uppercase text-sm">
                            Les Meves Reserves
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="text-red-400 hover:text-red-300 font-semibold tracking-wide transition-colors uppercase text-sm">
                            Sortir
                        </button>
                    </form>
                @else
                    {{-- Si no està autenticat, mostrar login/register --}}
                    <a href="{{ route('login') }}" 
                       class="text-gray-300 hover:text-white font-semibold tracking-wide transition-colors uppercase text-sm">
                        Login
                    </a>
                    <a href="{{ route('register') }}" 
                       class="bg-purple-600 hover:bg-purple-700 px-6 py-2.5 rounded-lg font-bold tracking-wide transition-colors uppercase text-sm">
                        Sol·licitar Tocar
                    </a>
                @endauth
            </div>

            {{-- Menú mobile (hamburger) --}}
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-white focus:outline-none">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Menú mobile desplegable --}}
    <div id="mobile-menu" class="hidden md:hidden bg-gray-900 border-t border-gray-800">
        <div class="px-4 py-3 space-y-3">
            <a href="/agenda" 
               class="block text-gray-300 hover:text-white font-semibold uppercase text-sm">
                Agenda
            </a>

            @auth
                @if(auth()->user()->isAdmin())
                    <a href="/admin/reserves" 
                       class="block text-gray-300 hover:text-white font-semibold uppercase text-sm">
                        Admin
                    </a>
                @elseif(auth()->user()->isArtista())
                    <a href="/artista/reservar" 
                       class="block text-gray-300 hover:text-white font-semibold uppercase text-sm">
                        Les Meves Reserves
                    </a>
                @endif

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="block w-full text-left text-red-400 hover:text-red-300 font-semibold uppercase text-sm">
                        Sortir
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" 
                   class="block text-gray-300 hover:text-white font-semibold uppercase text-sm">
                    Login
                </a>
                <a href="{{ route('register') }}" 
                   class="block bg-purple-600 hover:bg-purple-700 px-6 py-2.5 rounded-lg font-bold text-center uppercase text-sm">
                    Sol·licitar Tocar
                </a>
            @endauth
        </div>
    </div>
</nav>

{{-- Script per a toggle del menú mobile --}}
<script>
    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>