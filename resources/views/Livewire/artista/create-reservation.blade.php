<div>
    {{-- Header amb filtres --}}
    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-2xl font-bold text-white">Slots Disponibles</h2>
                <p class="text-gray-400 text-sm mt-1">Selecciona una data per sol·licitar tocar a LaSala</p>
            </div>
            
            {{-- Resum --}}
            <div class="flex gap-3">
                <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm">
                    {{ \App\Models\Slot::disponibles()->futurs()->count() }} slots disponibles
                </span>
            </div>
        </div>

        {{-- Filtre de data --}}
        <div class="flex gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Filtrar per data</label>
                <input type="date" 
                       wire:model.live="filterDate"
                       min="{{ date('Y-m-d') }}"
                       class="bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2">
            </div>

            @if($filterDate)
                <div class="flex items-end">
                    <button wire:click="$set('filterDate', '')" 
                            class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg">
                        Netejar filtre
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

    {{-- Taula de slots --}}
    @if($slots->count() > 0)
        <div class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Data
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Horari
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Estat
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Acció
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach($slots as $slot)
                        <tr class="hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 text-white">
                                {{ $slot->data->format('d/m/Y') }}
                                @if($slot->isToday())
                                    <span class="ml-2 text-xs bg-blue-500 text-white px-2 py-1 rounded">Avui</span>
                                @elseif($slot->isDema())
                                    <span class="ml-2 text-xs bg-green-500 text-white px-2 py-1 rounded">Demà</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-300">
                                {{ substr($slot->hora_inici, 0, 5) }} - {{ substr($slot->hora_fi, 0, 5) }}
                            </td>
                            <td class="px-6 py-4">
                                @if(in_array($slot->id, $slotsPendents))
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-500/20 text-yellow-400">
                                        Sol·licitud Pendent
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-400">
                                        Disponible
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if(in_array($slot->id, $slotsPendents))
                                    <span class="text-sm text-gray-500 italic">Esperant aprovació</span>
                                @else
                                    <button wire:click="openModal({{ $slot->id }})"
                                            class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold transition-colors">
                                        Sol·licitar
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginació --}}
        <div class="mt-6">
            {{ $slots->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-gray-800 rounded-lg border border-gray-700">
            <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="mt-4 text-gray-400">No hi ha slots disponibles amb els filtres seleccionats</p>
            @if($filterDate)
                <button wire:click="$set('filterDate', '')" 
                        class="mt-3 text-purple-400 hover:text-purple-300">
                    Veure tots els slots
                </button>
            @endif
        </div>
    @endif

    {{-- Modal de confirmació --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4 border border-gray-700">
                @php
                    $slot = \App\Models\Slot::find($selectedSlotId);
                @endphp

                <h3 class="text-xl font-bold text-white mb-4">Confirmar Sol·licitud</h3>
                
                @error('reserva')
                    <div class="mb-4 bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg">
                        {{ $message }}
                    </div>
                @enderror

                @if($slot)
                    <div class="mb-4 p-4 bg-gray-700 rounded-lg">
                        <p class="text-sm text-gray-400 mb-1">Data i hora seleccionades:</p>
                        <p class="text-lg font-semibold text-white">
                            {{ $slot->data->format('d/m/Y') }}
                        </p>
                        <p class="text-purple-400">
                            {{ substr($slot->hora_inici, 0, 5) }} - {{ substr($slot->hora_fi, 0, 5) }}
                        </p>
                    </div>
                @endif

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Notes (opcional)
                    </label>
                    <textarea wire:model="notes_artistes" 
                              rows="4" 
                              class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                              placeholder="Explica el teu estil, equipament, etc..."></textarea>
                    @error('notes_artistes')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Màxim 500 caràcters</p>
                </div>

                <div class="flex gap-3 justify-end">
                    <button wire:click="closeModal" 
                            class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg">
                        Cancel·lar
                    </button>
                    <button wire:click="createReservation" 
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold">
                        Enviar Sol·licitud
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>