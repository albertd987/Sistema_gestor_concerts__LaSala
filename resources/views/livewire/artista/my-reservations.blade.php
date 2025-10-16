<div>
    {{-- Header amb estadístiques --}}
    <div class="mb-6">
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-white">Les Meves Reserves</h2>
            <p class="text-gray-400 text-sm mt-1">Historial complet de les teves sol·licituds</p>
        </div>

        {{-- Estadístiques en cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            {{-- Pendents --}}
            <div class="bg-gray-800 border border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Pendents</p>
                        <p class="text-3xl font-bold text-yellow-400">{{ $stats['pendents'] }}</p>
                    </div>
                    <div class="bg-yellow-500/20 p-3 rounded-lg">
                        <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Aprovades --}}
            <div class="bg-gray-800 border border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Aprovades</p>
                        <p class="text-3xl font-bold text-green-400">{{ $stats['aprovades'] }}</p>
                    </div>
                    <div class="bg-green-500/20 p-3 rounded-lg">
                        <svg class="w-8 h-8 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Rebutjades --}}
            <div class="bg-gray-800 border border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Rebutjades</p>
                        <p class="text-3xl font-bold text-red-400">{{ $stats['rebutjades'] }}</p>
                    </div>
                    <div class="bg-red-500/20 p-3 rounded-lg">
                        <svg class="w-8 h-8 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filtre --}}
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-1">Filtrar per estat</label>
            <select wire:model.live="filterStatus" 
                    class="bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2">
                <option value="">Tots els estats</option>
                <option value="pendent">Pendents</option>
                <option value="aprovat">Aprovades</option>
                <option value="rebutjat">Rebutjades</option>
            </select>
        </div>
    </div>

    {{-- Llista de reserves --}}
    @if($reserves->count() > 0)
        <div class="space-y-4">
            @foreach($reserves as $reserva)
                <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 hover:border-purple-500/50 transition-colors">
                    <div class="flex justify-between items-start">
                        {{-- Info principal --}}
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                {{-- Data i hora --}}
                                <div class="flex items-center gap-2 text-white">
                                    <svg class="w-5 h-5 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-semibold text-lg">{{ $reserva->dataSlotFormatted }}</span>
                                </div>

                                {{-- Badge d'estat --}}
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($reserva->status->value === 'pendent') bg-yellow-500/20 text-yellow-400
                                    @elseif($reserva->status->value === 'aprovat') bg-green-500/20 text-green-400
                                    @else bg-red-500/20 text-red-400
                                    @endif">
                                    {{ $reserva->status->label() }}
                                </span>
                            </div>

                            {{-- Timeline / Info segons estat --}}
                            <div class="space-y-2 text-sm">
                                {{-- Sol·licitud enviada --}}
                                <div class="flex items-start gap-2 text-gray-400">
                                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p><strong>Sol·licitud enviada:</strong> {{ $reserva->created_at->format('d/m/Y H:i') }}</p>
                                        <p class="text-xs text-gray-500">Fa {{ $reserva->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                {{-- Si aprovada --}}
                                @if($reserva->status->esAprovada())
                                    <div class="flex items-start gap-2 text-green-400">
                                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <div>
                                            <p><strong>Aprovada:</strong> {{ $reserva->aprovat_a->format('d/m/Y H:i') }}</p>
                                            <p class="text-xs">per {{ $reserva->aprovador->name }}</p>
                                        </div>
                                    </div>
                                @endif

                                {{-- Si rebutjada --}}
                                @if($reserva->status->esRebutjada())
                                    <div class="flex items-start gap-2 text-red-400">
                                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        <p><strong>Rebutjada</strong></p>
                                    </div>
                                @endif
                            </div>

                            {{-- Notes de l'artista --}}
                            @if($reserva->notes_artistes)
                                <div class="mt-3 p-3 bg-gray-700/50 rounded-lg">
                                    <p class="text-sm text-gray-300">
                                        <strong class="text-purple-400">Les teves notes:</strong><br>
                                        {{ $reserva->notes_artistes }}
                                    </p>
                                </div>
                            @endif

                            {{-- Notes de l'admin (si rebutjada) --}}
                            @if($reserva->status->esRebutjada() && $reserva->notes_admin)
                                <div class="mt-3 p-3 bg-red-500/10 border border-red-500/30 rounded-lg">
                                    <p class="text-sm text-red-400">
                                        <strong>Motiu del rebuig:</strong><br>
                                        {{ $reserva->notes_admin }}
                                    </p>
                                </div>
                            @endif

                            {{-- Missatge si pendent --}}
                            @if($reserva->status->esPendent())
                                <div class="mt-3 p-3 bg-yellow-500/10 border border-yellow-500/30 rounded-lg">
                                    <p class="text-sm text-yellow-400">
                                        ⏳ La teva sol·licitud està pendent de revisió per part de l'administrador
                                    </p>
                                </div>
                            @endif

                            {{-- Missatge si aprovada --}}
                            @if($reserva->status->esAprovada())
                                <div class="mt-3 p-3 bg-green-500/10 border border-green-500/30 rounded-lg">
                                    <p class="text-sm text-green-400">
                                        🎉 Confirmada! Tocaràs a LaSala el {{ $reserva->slot->data->format('d/m/Y') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginació --}}
        <div class="mt-6">
            {{ $reserves->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-gray-800 rounded-lg border border-gray-700">
            <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="mt-4 text-gray-400">
                @if($filterStatus)
                    No tens cap reserva amb aquest estat
                @else
                    Encara no has fet cap sol·licitud
                @endif
            </p>
            @if($filterStatus)
                <button wire:click="$set('filterStatus', '')" 
                        class="mt-3 text-purple-400 hover:text-purple-300">
                    Veure totes les reserves
                </button>
            @else
                <a href="/artista/reservar" 
                   class="mt-3 inline-block text-purple-400 hover:text-purple-300">
                    Sol·licitar el teu primer slot →
                </a>
            @endif
        </div>
    @endif
</div>