<?php

namespace App\Livewire\Public;

use App\Models\Reserva;
use Livewire\Component;

class EventDetail extends Component
{
    /**
     * ID de la reserva (esdeveniment)
     */
    public $eventId;

    /**
     * Reserva (esdeveniment) carregat
     */
    public $event;

    /**
     * Inicialitzar component
     */
    public function mount($eventId)
    {
        $this->eventId = $eventId;
        
        // Carregar esdeveniment amb relacions
        $this->event = Reserva::with(['artista.usuari', 'slot'])
            ->findOrFail($eventId);

        // Verificar que l'esdeveniment està aprovat
        if (!$this->event->status->esAprovada()) {
            abort(404, 'Esdeveniment no disponible');
        }
    }

    /**
     * Tornar a l'agenda
     */
    public function backToAgenda()
    {
        return redirect('/agenda');
    }

    /**
     * Renderitzar component
     */
    public function render()
    {
        return view('livewire.public.event-detail');
    }
}