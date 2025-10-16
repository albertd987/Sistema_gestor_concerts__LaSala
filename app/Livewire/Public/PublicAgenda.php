<?php

namespace App\Livewire\Public;

use App\Models\Reserva;
use Livewire\Component;
use Livewire\WithPagination;

class PublicAgenda extends Component
{
    use WithPagination;

    /**
     * Filtre per gènere musical
     */
    public $filterGenre = '';

    /**
     * Llista de gèneres disponibles (obtinguts dels artistes)
     */
    public $availableGenres = [];

    /**
     * Inicialitzar component
     */
    public function mount()
    {
        // Obtenir tots els gèneres únics dels artistes amb reserves aprovades
        $this->availableGenres = Reserva::aprovades()
            ->with('artista')
            ->get()
            ->pluck('artista.genere')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }

    /**
     * Resetar paginació quan canvia el filtre
     */
    public function updatedFilterGenre()
    {
        $this->resetPage();
    }

    /**
     * Renderitzar component
     */
    public function render()
    {
        $query = Reserva::aprovades()
            ->with(['artista.usuari', 'slot'])
            ->ordenadesPorData();

        // Aplicar filtre de gènere si està actiu
        if ($this->filterGenre) {
            $query->whereHas('artista', function ($q) {
                $q->where('genere', $this->filterGenre);
            });
        }

        $events = $query->paginate(12);

        return view('livewire.public.public-agenda', [
            'events' => $events,
        ]);
    }
}