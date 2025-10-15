<?php

namespace App\Livewire\Artista;

use App\Enums\EstatReserva;
use App\Models\Artista;
use App\Models\Reserva;
use App\Models\Slot;
use Livewire\Component;
use Livewire\WithPagination;

class CreateReservation extends Component
{
    use WithPagination;

    // Control del modal
    public $showModal = false;
    public $selectedSlotId = null;
    public $notes_artistes = '';

    // Filtres
    public $filterDate = '';

    // Resetejar paginació quan canvien filtres
    public function updatingFilterDate()
    {
        $this->resetPage();
    }

    /**
     * Obrir modal per confirmar reserva
     */
    public function openModal($slotId)
    {
        $this->selectedSlotId = $slotId;
        $this->notes_artistes = '';
        $this->showModal = true;
    }

    /**
     * Crear la reserva
     */
    public function createReservation()
    {
        $this->validate([
            'notes_artistes' => 'nullable|string|max:500',
        ], [
            'notes_artistes.max' => 'Les notes no poden superar els 500 caràcters',
        ]);

        try {
            // Obtenir l'artista de l'usuari actual
            $artista = auth()->user()->artista;

            if (!$artista) {
                $this->addError('reserva', 'No tens un perfil d\'artista associat');
                return;
            }

            // Verificar que el slot encara està disponible
            $slot = Slot::findOrFail($this->selectedSlotId);
            
            if (!$slot->isAvailable()) {
                $this->addError('reserva', 'Aquest slot ja no està disponible');
                return;
            }

            // Verificar que l'artista no té ja una reserva PENDENT per aquest slot
            $reservaExistent = Reserva::where('id_artista', $artista->id)
                ->where('id_slot', $slot->id)
                ->where('status', EstatReserva::PENDENT)
                ->exists();

            if ($reservaExistent) {
                $this->addError('reserva', 'Ja tens una sol·licitud pendent per aquest slot');
                return;
            }

            // Crear la reserva
            Reserva::create([
                'id_artista' => $artista->id,
                'id_slot' => $slot->id,
                'status' => EstatReserva::PENDENT,
                'notes_artistes' => $this->notes_artistes,
            ]);

            session()->flash('message', 'Sol·licitud enviada correctament! Espera la confirmació de l\'administrador.');
            $this->closeModal();

        } catch (\Exception $e) {
            $this->addError('reserva', 'Error al crear la reserva: ' . $e->getMessage());
        }
    }

    /**
     * Tancar modal
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedSlotId = null;
        $this->notes_artistes = '';
        $this->resetErrorBag();
    }

    /**
     * Render
     */
    public function render()
    {
        // Obtenir l'artista actual
        $artista = auth()->user()->artista;

        // Només slots disponibles i futurs
        $query = Slot::disponibles()
            ->futurs()
            ->ordenatsPerData();

        // Aplicar filtre de data si existeix
        if ($this->filterDate) {
            $query->where('data', $this->filterDate);
        }

        $slots = $query->paginate(10);

        // Obtenir IDs de slots amb reserves pendents d'aquest artista
        $slotsPendents = [];
        if ($artista) {
            $slotsPendents = Reserva::where('id_artista', $artista->id)
                ->where('status', EstatReserva::PENDENT)
                ->pluck('id_slot')
                ->toArray();
        }

        return view('livewire.artista.create-reservation', [
            'slots' => $slots,
            'slotsPendents' => $slotsPendents,
        ]);
    }
}