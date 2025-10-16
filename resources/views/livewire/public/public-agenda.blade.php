<div class="min-h-screen">
    {{-- Hero Section --}}
    <div class="relative h-[60vh] overflow-hidden">
        {{-- Imagen de fondo --}}
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=1920&h=1080&fit=crop" 
                 alt="LaSala" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black"></div>
        </div>

        {{-- Contingut del hero --}}
        <div class="relative h-full flex items-center justify-center">
            <div class="text-center px-4">
                <h1 class="text-6xl md:text-8xl font-black text-white mb-4 tracking-tighter">
                    CONCERTS
                </h1>
                <p class="text-xl md:text-2xl text-gray-300 font-light tracking-wide">
                    Descobreix els millors artistes a LaSala
                </p>
            </div>
        </div>
    </div>

    {{-- Contingut principal --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- Filtres de gènere --}}
        @if(count($availableGenres) > 0)
            <div class="mb-12">
                <div class="flex flex-wrap gap-3 justify-center">
                    {{-- Botó "Tots" --}}
                    <button wire:click="$set('filterGenre', '')"
                            class="px-6 py-2.5 rounded-full font-bold uppercase text-sm tracking-wide transition-all
                                   {{ $filterGenre === '' 
                                      ? 'bg-purple-600 text-white' 
                                      : 'bg-gray-800 text-gray-300 hover:bg-gray-700' }}">
                        Tots
                    </button>

                    {{-- Botó per cada gènere --}}
                    @foreach($availableGenres as $genre)
                        <button wire:click="$set('filterGenre', '{{ $genre }}')"
                                class="px-6 py-2.5 rounded-full font-bold uppercase text-sm tracking-wide transition-all
                                       {{ $filterGenre === $genre 
                                          ? 'bg-purple-600 text-white' 
                                          : 'bg-gray-800 text-gray-300 hover:bg-gray-700' }}">
                            {{ $genre }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Grid d'esdeveniments --}}
        @if($events->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($events as $event)
                    <a href="/agenda/{{ $event->id }}" 
                       class="group bg-gray-900 rounded-xl overflow-hidden border border-gray-800 hover:border-purple-600 transition-all duration-300 hover:transform hover:scale-105">
                        
                        {{-- Imatge del poster --}}
                        <div class="relative h-80 overflow-hidden">
                            <img src="{{ $event->poster }}" 
                                 alt="{{ $event->nomArtista }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            
                            {{-- Overlay gradient --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/20 to-transparent"></div>
                            
                            {{-- Badge de data --}}
                            <div class="absolute top-4 left-4">
                                <div class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold">
                                    {{ \Carbon\Carbon::parse($event->slot->data)->locale('ca')->isoFormat('ddd DD MMM') }}
                                </div>
                            </div>
                        </div>

                        {{-- Info de l'esdeveniment --}}
                        <div class="p-6">
                            {{-- Hora --}}
                            <div class="flex items-center gap-2 text-gray-400 text-sm mb-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold">
                                    {{ \Carbon\Carbon::parse($event->slot->hora_inici)->format('H:i') }}
                                </span>
                            </div>

                            {{-- Nom de l'artista --}}
                            <h3 class="text-2xl font-black text-white mb-3 group-hover:text-purple-400 transition-colors line-clamp-2">
                                {{ $event->nomArtista }}
                            </h3>

                            {{-- Badge de gènere --}}
                            @if($event->artista->genere)
                                <span class="inline-block px-3 py-1 bg-gray-800 text-gray-300 text-xs font-semibold rounded-full uppercase">
                                    {{ $event->artista->genere }}
                                </span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Paginació --}}
            <div class="mt-12">
                {{ $events->links() }}
            </div>
        @else
            {{-- Missatge quan no hi ha esdeveniments --}}
            <div class="text-center py-20">
                <svg class="mx-auto h-16 w-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                </svg>
                <h3 class="text-2xl font-bold text-white mb-2">
                    Cap concert programat
                </h3>
                <p class="text-gray-400">
                    @if($filterGenre)
                        No hi ha concerts de {{ $filterGenre }} programats actualment.
                    @else
                        Aviat hi haurà nous concerts. Torna més tard!
                    @endif
                </p>
                @if($filterGenre)
                    <button wire:click="$set('filterGenre', '')"
                            class="mt-6 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition-colors">
                        Veure tots els concerts
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>