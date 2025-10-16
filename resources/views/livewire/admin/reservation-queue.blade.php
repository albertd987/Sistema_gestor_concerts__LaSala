<div>
    {{-- Header amb filtres --}}
    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-white">Cua de Reserves</h2>
            
            {{-- Resum ràpid --}}
            <div class="flex gap-3">
                <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-sm">
                    {{ \App\Models\Reserva::pendents()->count() }} pendents
                </span>
                <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm">
                    {{ \App\Models\Reserva::aprovades()->count() }} aprovades
                </span>
            </div>
        </div>

        {{-- Filtres --}}
        <div class="flex gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Estat</label>
                <select wire:model.live="filterStatus" 
                        class="bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2">
                    <option value="">Tots</option>
                    <option value="pendent">Pendents</option>
                    <option value="aprovat">Aprovades</option>
                    <option value="rebutjat">Rebutjades</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Data del slot</label>
                <input type="date" 
                       wire:model.live="filterDate" 
                       class="bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2">
            </div>

            @if($filterDate)
                <div class="flex items-end">
                    <button wire:click="$set('filterDate', '')" 
                            class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg">
                        Netejar filtres
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="mb-4 bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Llista de reserves (tarjetes) --}}
    @if($reserves->count() > 0)
        <div class="space-y-4">
            @foreach($reserves as $reserva)
                <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 hover:border-purple-500/50 transition-colors">
                    <div class="flex justify-between items-start">
                        {{-- Info principal --}}
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                {{-- Nom artista --}}
                                <h3 class="text-xl font-bold text-white">
                                    {{ $reserva->nomArtista }}
                                </h3>
                                
                                {{-- Badge d'estat --}}
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($reserva->status->value === 'pendent') bg-yellow-500/20 text-yellow-400
                                    @elseif($reserva->status->value === 'aprovat') bg-green-500/20 text-green-400
                                    @else bg-red-500/20 text-red-400
                                    @endif">
                                    {{ $reserva->status->label() }}
                                </span>

                                {{-- Badge d'urgència (>3 dies) --}}
                                @if($reserva->esUrgent())
                                    <span class="px-3 py-1 bg-red-500 text-white rounded-full text-xs font-bold animate-pulse">
                                        ⚠️ Urgent ({{ $reserva->diesDesDeSolicitud() }} dies)
                                    </span>
                                @endif
                            </div>

                            {{-- Data del slot --}}
                            <div class="flex items-center gap-2 text-gray-300 mb-3">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold">{{ $reserva->dataSlotFormatted }}</span>
                            </div>

                            {{-- Info de l'artista --}}
                            <div class="space-y-1 text-sm text-gray-400">
                                <p><strong>Gènere:</strong> {{ $reserva->artista->genere }}</p>
                                <p><strong>Contacte:</strong> {{ $reserva->artista->email() }}</p>
                                @if($reserva->artista->tlf_contacte)
                                    <p><strong>Telèfon:</strong> {{ $reserva->artista->tlf_contacte }}</p>
                                @endif
                            </div>

                            {{-- Notes de l'artista --}}
                            @if($reserva->notes_artistes)
                                <div class="mt-3 p-3 bg-gray-700/50 rounded-lg">
                                    <p class="text-sm text-gray-300">
                                        <strong class="text-purple-400">Notes de l'artista:</strong><br>
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

                            {{-- Info d'aprovació --}}
                            @if($reserva->status->esAprovada())
                                <div class="mt-3 text-xs text-gray-500">
                                    Aprovada per {{ $reserva->aprovador->name }} 
                                    el {{ $reserva->aprovat_a->format('d/m/Y H:i') }}
                                </div>
                            @endif
                        </div>

                        {{-- Accions --}}
                        @if($reserva->status->esPendent())
                            <div class="flex gap-2 ml-4">
                                <button wire:click="openAprovar({{ $reserva->id }})"
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-colors flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Aprovar
                                </button>

                                <button wire:click="openRebutjar({{ $reserva->id }})"
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition-colors flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    Rebutjar
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginació --}}
        <div class="mt-6">
            {{ $reserves->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-gray-800 rounded-lg">
            <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="mt-4 text-gray-400">No hi ha reserves amb els filtres seleccionats</p>
        </div>
    @endif

    {{-- Modal Aprovar --}}
    @if($showModal && $modalType === 'aprovar')
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4 border border-gray-700">
                <h3 class="text-xl font-bold text-white mb-4">Confirmar Aprovació</h3>
                
                @error('reserva')
                    <div class="mb-4 bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg">
                        {{ $message }}
                    </div>
                @enderror

                <p class="text-gray-300 mb-6">
                    Estàs segur que vols <strong>aprovar</strong> aquesta reserva? 
                    El slot quedarà marcat com a reservat i apareixerà a l'agenda pública.
                </p>

                <div class="flex gap-3 justify-end">
                    <button wire:click="closeModal" 
                            class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg">
                        Cancel·lar
                    </button>
                    <button wire:click="aprovar" 
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold">
                        Sí, Aprovar
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Rebutjar --}}
    @if($showModal && $modalType === 'rebutjar')
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4 border border-gray-700">
                <h3 class="text-xl font-bold text-white mb-4">Rebutjar Reserva</h3>
                
                @error('reserva')
                    <div class="mb-4 bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg">
                        {{ $message }}
                    </div>
                @enderror

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Motiu del rebuig <span class="text-red-400">*</span>
                    </label>
                    <textarea wire:model="notes_admin" 
                              rows="4" 
                              class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                              placeholder="Explica per què rebutges aquesta reserva..."></textarea>
                    @error('notes_admin')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 justify-end">
                    <button wire:click="closeModal" 
                            class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg">
                        Cancel·lar
                    </button>
                    <button wire:click="rebutjar" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold">
                        Rebutjar Reserva
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>