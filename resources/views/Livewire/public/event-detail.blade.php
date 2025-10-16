<div class="min-h-screen">
    {{-- Hero amb imatge del poster --}}
    <div class="relative h-[60vh] overflow-hidden">
        {{-- Imatge de fons --}}
        <div class="absolute inset-0">
            <img src="{{ $event->poster }}" 
                 alt="{{ $event->nomArtista }}"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black"></div>
        </div>

        {{-- Botó tornar --}}
        <div class="absolute top-6 left-6 z-10">
            <button wire:click="backToAgenda"
                    class="flex items-center gap-2 bg-black/50 backdrop-blur-sm hover:bg-black/70 text-white px-3 py-2 rounded-md transition-colors text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="font-medium">Tornar</span>
            </button>
        </div>

        {{-- Contingut del hero --}}
        <div class="relative h-full flex items-end">
            <div class="w-full px-6 pb-8">
                <div class="max-w-6xl mx-auto">
                    {{-- Badges d'info --}}
                    <div class="flex flex-wrap gap-2 mb-4">
                        {{-- Hora --}}
                        <div class="flex items-center gap-1.5 bg-black/50 backdrop-blur-sm px-3 py-1.5 rounded-md text-sm">
                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-white font-medium">{{ \Carbon\Carbon::parse($event->slot->hora_inici)->format('H:i') }}h</span>
                        </div>

                        {{-- Gènere --}}
                        @if($event->artista->genere)
                            <div class="flex items-center gap-1.5 bg-black/50 backdrop-blur-sm px-3 py-1.5 rounded-md text-sm">
                                <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"/>
                                </svg>
                                <span class="text-white font-medium uppercase text-xs tracking-wide">{{ $event->artista->genere }}</span>
                            </div>
                        @endif

                        {{-- Ubicació --}}
                        <div class="flex items-center gap-1.5 bg-black/50 backdrop-blur-sm px-3 py-1.5 rounded-md text-sm">
                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-white font-medium">LaSala Igualada</span>
                        </div>
                    </div>

                    {{-- Títol de l'esdeveniment --}}
                    <h1 class="text-5xl md:text-7xl font-black text-white mb-0 tracking-tight">
                        {{ $event->nomArtista }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    {{-- Contingut principal --}}
    <div class="max-w-6xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Columna principal: Info de l'artista --}}
            <div class="lg:col-span-2 space-y-10">
                
                {{-- Bio de l'artista --}}
                @if($event->artista->bio)
                    <div>
                        <h2 class="text-xl font-bold text-white mb-3 uppercase tracking-wide">
                            Sobre l'Artista
                        </h2>
                        <p class="text-gray-300 leading-relaxed">
                            {{ $event->artista->bio }}
                        </p>
                    </div>
                @endif

                {{-- Notes de l'artista (si n'hi ha) --}}
                @if($event->notes_artistes)
                    <div>
                        <h2 class="text-xl font-bold text-white mb-3 uppercase tracking-wide">
                            Informació Addicional
                        </h2>
                        <div class="bg-gray-900/50 rounded-lg p-4 border-l-4 border-purple-600">
                            <p class="text-gray-300 leading-relaxed">
                                {{ $event->notes_artistes }}
                            </p>
                        </div>
                    </div>
                @endif

                {{-- Xarxes socials --}}
                @if($event->artista->links_socials && count($event->artista->links_socials) > 0)
                    <div>
                        <h2 class="text-xl font-bold text-white mb-3 uppercase tracking-wide">
                            Xarxes Socials
                        </h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($event->artista->links_socials as $platform => $url)
                                <a href="{{ $url }}" 
                                   target="_blank"
                                   class="flex items-center gap-2 bg-gray-900 hover:bg-purple-600 px-4 py-2 rounded-md transition-colors text-sm">
                                    <span class="text-white font-medium lowercase">{{ $platform }}</span>
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Columna lateral: Info pràctica --}}
            <div>
                {{-- Card: Detalls de l'esdeveniment --}}
                <div class="bg-[#1a1d29] rounded-lg p-5">
                    <h3 class="text-sm font-bold text-white mb-4 uppercase tracking-wider">
                        Detalls
                    </h3>
                    
                    <div class="space-y-3">
                        {{-- Data --}}
                        <div>
                            <p class="text-gray-500 text-xs font-semibold uppercase mb-0.5">Data</p>
                            <p class="text-white font-medium">
                                {{ \Carbon\Carbon::parse($event->slot->data)->locale('ca')->isoFormat('DD MMMM YYYY') }}
                            </p>
                        </div>

                        {{-- Hora --}}
                        <div>
                            <p class="text-gray-500 text-xs font-semibold uppercase mb-0.5">Hora d'inici</p>
                            <p class="text-white font-medium">
                                {{ \Carbon\Carbon::parse($event->slot->hora_inici)->format('H:i') }}h
                            </p>
                        </div>

                        {{-- Hora fi --}}
                        <div>
                            <p class="text-gray-500 text-xs font-semibold uppercase mb-0.5">Hora de finalització</p>
                            <p class="text-white font-medium">
                                {{ \Carbon\Carbon::parse($event->slot->hora_fi)->format('H:i') }}h
                            </p>
                        </div>

                        {{-- Gènere --}}
                        @if($event->artista->genere)
                            <div>
                                <p class="text-gray-500 text-xs font-semibold uppercase mb-0.5">Gènere</p>
                                <p class="text-white font-medium">
                                    {{ $event->artista->genere }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>