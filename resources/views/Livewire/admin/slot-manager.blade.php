<div class="p-6">
    {{-- Header --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white">Gestió de Slots</h1>
            <p class="text-gray-400 mt-1">Crea i gestiona els horaris disponibles per reserves</p>
        </div>
        <button wire:click="create" 
                class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition">
            + Crear Slot
        </button>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="mb-4 bg-green-500/10 border border-green-500 text-green-500 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Filtres --}}
    <div class="mb-6 bg-gray-800 p-4 rounded-lg grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Filtrar per estat</label>
            <select wire:model.live="filterStatus" 
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2">
                <option value="">Tots els estats</option>
                @foreach($statusOptions as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Filtrar per data</label>
            <input type="date" 
                   wire:model.live="filterDate"
                   class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2">
        </div>

        <div class="flex items-end">
            <button wire:click="$set('filterStatus', ''); $set('filterDate', '')" 
                    class="w-full bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                Netejar Filtres
            </button>
        </div>
    </div>

    {{-- Taula de Slots --}}
    <div class="bg-gray-800 rounded-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Data</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Horari</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Estat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Reserva</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase">Accions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($slots as $slot)
                    <tr class="hover:bg-gray-700/50 transition">
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
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($slot->status->value === 'disponible') bg-green-500/20 text-green-400
                                @elseif($slot->status->value === 'reservat') bg-red-500/20 text-red-400
                                @else bg-gray-500/20 text-gray-400
                                @endif">
                                {{ $slot->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-300">
                            @if($slot->reservaAprovada)
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                    </svg>
                                    <span>{{ $slot->reservaAprovada->artista->nomGrup }}</span>
                                </div>
                            @else
                                <span class="text-gray-500">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button wire:click="edit({{ $slot->id }})"
                                        class="text-blue-400 hover:text-blue-300 p-2 rounded hover:bg-gray-700 transition"
                                        title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                <button wire:click="toggleBlock({{ $slot->id }})"
                                        class="text-yellow-400 hover:text-yellow-300 p-2 rounded hover:bg-gray-700 transition"
                                        title="{{ $slot->status->value === 'bloquejat' ? 'Desbloquejar' : 'Bloquejar' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($slot->status->value === 'bloquejat')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        @endif
                                    </svg>
                                </button>

                                <button wire:click="delete({{ $slot->id }})"
                                        wire:confirm="Segur que vols eliminar aquest slot?"
                                        class="text-red-400 hover:text-red-300 p-2 rounded hover:bg-gray-700 transition"
                                        title="Eliminar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="mt-2">No hi ha slots disponibles</p>
                            <button wire:click="create" class="mt-4 text-purple-400 hover:text-purple-300">
                                Crear el primer slot
                            </button>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-6 py-4 bg-gray-700/50">
            {{ $slots->links() }}
        </div>
    </div>

    {{-- Modal Crear/Editar --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black/75 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md">
                <h2 class="text-2xl font-bold text-white mb-4">
                    {{ $editingSlotId ? 'Editar Slot' : 'Crear Nou Slot' }}
                </h2>

                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Data</label>
                        <input type="date" wire:model="data" 
                               class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2">
                        @error('data') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Hora d'inici</label>
                        <input type="time" wire:model="hora_inici" 
                               class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2">
                        @error('hora_inici') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Hora de fi</label>
                        <input type="time" wire:model="hora_fi" 
                               class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2">
                        @error('hora_fi') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Estat</label>
                        <select wire:model="status" 
                                class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2">
                            @foreach($statusOptions as $statusOption)
                                <option value="{{ $statusOption->value }}">{{ $statusOption->label() }}</option>
                            @endforeach
                        </select>
                        @error('status') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" wire:click="closeModal"
                                class="flex-1 bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                            Cancel·lar
                        </button>
                        <button type="submit"
                                class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition">
                            {{ $editingSlotId ? 'Actualitzar' : 'Crear' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>