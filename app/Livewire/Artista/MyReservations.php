<?php

namespace App\Livewire\Artista;

use App\Models\Reserva;
use Livewire\Component;
use Livewire\WithPagination;

class MyReservations extends Component
{
    use WithPagination;

    // Filtres
    public $filterStatus = '';

    // Resetejar paginació quan canvien filtres
    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    /**
     * Render
     */
    public function render()
    {
        $artista = auth()->user()->artista;

        if (!$artista) {
            return view('livewire.artista.my-reservations', [
                'reserves' => collect([]),
                'stats' => [
                    'pendents' => 0,
                    'aprovades' => 0,
                    'rebutjades' => 0,
                ],
            ]);
        }

        // Query base
        $query = Reserva::with(['slot'])
            ->where('id_artista', $artista->id)
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

        $reserves = $query->paginate(10);

        // Estadístiques
        $stats = [
            'pendents' => Reserva::where('id_artista', $artista->id)->pendents()->count(),
            'aprovades' => Reserva::where('id_artista', $artista->id)->aprovades()->count(),
            'rebutjades' => Reserva::where('id_artista', $artista->id)->rebutjades()->count(),
        ];

        return view('livewire.artista.my-reservations', [
            'reserves' => $reserves,
            'stats' => $stats,
        ]);
    }
}