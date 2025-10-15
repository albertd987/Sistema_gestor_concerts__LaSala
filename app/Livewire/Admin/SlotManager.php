<?php

namespace App\Livewire\Admin;

use App\Enums\SlotStatus;
use App\Models\Slot;
use Livewire\Component;
use Livewire\WithPagination;

class SlotManager extends Component
{
    use WithPagination;

    // Propietats del formulari
    public $data;
    public $hora_inici;
    public $hora_fi;
    public $status = 'disponible';

    // Control del modal
    public $showModal = false;
    public $editingSlotId = null;

    // Filtres
    public $filterStatus = '';
    public $filterDate = '';

    // Regles de validació
    protected function rules()
    {
        return [
            'data' => 'required|date|after_or_equal:today',
            'hora_inici' => 'required|date_format:H:i',
            'hora_fi' => 'required|date_format:H:i|after:hora_inici',
            'status' => 'required|in:disponible,reservat,bloquejat',
        ];
    }

    // Missatges de validació personalitzats
    protected $messages = [
        'data.required' => 'La data és obligatòria',
        'data.after_or_equal' => 'La data no pot ser en el passat',
        'hora_inici.required' => "L'hora d'inici és obligatòria",
        'hora_inici.date_format' => 'Format incorrecte (HH:MM)',
        'hora_fi.required' => "L'hora de fi és obligatòria",
        'hora_fi.after' => "L'hora de fi ha de ser posterior a l'hora d'inici",
    ];

    // Resetejar paginació quan es filtren resultats
    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterDate()
    {
        $this->resetPage();
    }

    /**
     * Obrir modal per crear nou slot
     */
    public function create()
    {
        $this->reset(['data', 'hora_inici', 'hora_fi', 'status', 'editingSlotId']);
        $this->status = 'disponible';
        $this->showModal = true;
    }

    /**
     * Obrir modal per editar slot existent
     */
    public function edit($slotId)
    {
        $slot = Slot::findOrFail($slotId);
        
        $this->editingSlotId = $slot->id;
        $this->data = $slot->data->format('Y-m-d');
        $this->hora_inici = substr($slot->hora_inici, 0, 5);
        $this->hora_fi = substr($slot->hora_fi, 0, 5);
        $this->status = $slot->status->value;
        
        $this->showModal = true;
    }

    /**
     * Guardar slot (crear o actualitzar)
     */
    public function save()
    {
        $this->validate();

        try {
            if ($this->editingSlotId) {
                // Actualitzar slot existent
                $slot = Slot::findOrFail($this->editingSlotId);
                $slot->update([
                    'data' => $this->data,
                    'hora_inici' => $this->hora_inici . ':00',
                    'hora_fi' => $this->hora_fi . ':00',
                    'status' => $this->status,
                ]);
                
                session()->flash('message', 'Slot actualitzat correctament!');
            } else {
                // Crear nou slot
                Slot::create([
                    'data' => $this->data,
                    'hora_inici' => $this->hora_inici . ':00',
                    'hora_fi' => $this->hora_fi . ':00',
                    'status' => $this->status,
                ]);
                
                session()->flash('message', 'Slot creat correctament!');
            }

            $this->closeModal();
            
        } catch (\Illuminate\Database\QueryException $e) {
            // Error de constraint único (slot duplicat)
            if ($e->errorInfo[1] === 1062) {
                $this->addError('data', 'Ja existeix un slot amb aquesta data i horari');
            } else {
                $this->addError('data', 'Error al guardar el slot');
            }
        }
    }

    /**
     * Eliminar slot
     */
    public function delete($slotId)
    {
        try {
            $slot = Slot::findOrFail($slotId);
            
            // Verificar que no tingui reserves aprovades
            if ($slot->reservaAprovada()->exists()) {
                session()->flash('error', 'No es pot eliminar un slot amb reserves aprovades');
                return;
            }
            
            $slot->delete();
            session()->flash('message', 'Slot eliminat correctament!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el slot');
        }
    }

    /**
     * Bloquejar/Desbloquejar slot
     */
    public function toggleBlock($slotId)
    {
        $slot = Slot::findOrFail($slotId);
        
        if ($slot->status === SlotStatus::BLOQUEJAT) {
            $slot->alliberar();
            session()->flash('message', 'Slot desbloquejat');
        } else {
            $slot->bloquejar();
            session()->flash('message', 'Slot bloquejat');
        }
    }

    /**
     * Tancar modal
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['data', 'hora_inici', 'hora_fi', 'status', 'editingSlotId']);
        $this->resetValidation();
    }

    /**
     * Renderitzar component
     */
    public function render()
    {
        // Query base
        $query = Slot::query()->with('reservaAprovada.artista');

        // Aplicar filtres
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterDate) {
            $query->whereDate('data', $this->filterDate);
        }

        // Ordenar i paginar
        $slots = $query->ordenatsPerData()->paginate(10);

        return view('livewire.admin.slot-manager', [
            'slots' => $slots,
            'statusOptions' => SlotStatus::cases(),
        ]);
    }
}