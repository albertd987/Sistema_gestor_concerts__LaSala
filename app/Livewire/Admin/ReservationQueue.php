<?php

namespace App\Livewire\Admin;

use App\Enums\EstatReserva;
use App\Models\Reserva;
use Livewire\Component;
use Livewire\WithPagination;

class ReservationQueue extends Component
{
    use WithPagination;

    // Propietats del modal
    public $showModal = false;
    public $modalType = ''; // 'aprovar' o 'rebutjar'
    public $selectedReservaId = null;
    public $notes_admin = ''; // Per al cas de rebutjar

    // Filtres
    public $filterStatus = 'pendent'; // Per defecte només pendents
    public $filterDate = '';

    // Resetear paginació quan canvien els filtres
    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterDate()
    {
        $this->resetPage();
    }

    /**
     * Obrir modal per aprovar reserva
     */
    public function openAprovar($reservaId)
    {
        $this->selectedReservaId = $reservaId;
        $this->modalType = 'aprovar';
        $this->showModal = true;
    }

    /**
     * Obrir modal per rebutjar reserva
     */
    public function openRebutjar($reservaId)
    {
        $this->selectedReservaId = $reservaId;
        $this->modalType = 'rebutjar';
        $this->notes_admin = '';
        $this->showModal = true;
    }

    /**
     * Aprovar la reserva seleccionada
     */
public function aprovar()
{
    try {
        $reserva = Reserva::with(['slot', 'artista'])->findOrFail($this->selectedReservaId);
        $reserva->aprovar(auth()->user());

        } catch (\App\Excepcions\SlotNoDisponibleException $e) {
            $this->addError('reserva', 'Aquest slot ja no està disponible');
        } catch (\App\Excepcions\InvalidStateTransitionException $e) {
            $this->addError('reserva', 'No es pot aprovar aquesta reserva en el seu estat actual');
        } catch (\Exception $e) {
            $this->addError('reserva', 'Error al aprovar la reserva: ' . $e->getMessage());
        }
    }

    /**
     * Rebutjar la reserva seleccionada
     */
public function rebutjar()
{
    $this->validate([
        'notes_admin' => 'required|min:10',
    ], [
        'notes_admin.required' => 'Has de proporcionar un motiu per rebutjar',
        'notes_admin.min' => 'El motiu ha de tenir almenys 10 caràcters',
    ]);

    try {
        $reserva = Reserva::findOrFail($this->selectedReservaId);
        
        $reserva->rebutjar(auth()->user(), $this->notes_admin);
        } catch (\Exception $e) {
            $this->addError('reserva', 'Error al rebutjar la reserva: ' . $e->getMessage());
        }
    }

    /**
     * Tancar modal i resetejar
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->modalType = '';
        $this->selectedReservaId = null;
        $this->notes_admin = '';
        $this->resetErrorBag();
    }

    /**
     * Render del component
     */
    public function render()
    {
        $query = Reserva::with(['artista.usuari', 'slot'])
            ->ordenadesPorData();

        // Aplicar filtre d'estat
        if ($this->filterStatus) {
            switch ($this->filterStatus) {
                case 'pendent':
                    $query->pendents();
                    break;
                case 'aprovat':
                    $query->aprovades();
                    break;
                case 'rebutjat':
                    $query->rebutjades();
                    break;
            }
        }

        // Aplicar filtre de data (si el slot és d'aquella data)
        if ($this->filterDate) {
            $query->whereHas('slot', function ($q) {
                $q->where('data', $this->filterDate);
            });
        }

        $reserves = $query->paginate(10);

        return view('livewire.admin.reservation-queue', [
            'reserves' => $reserves,
        ]);
    }
}