<?php

namespace App\Models;

use App\Enums\EstatReserva;
use App\Enums\SlotStatus;
use App\Excepcions\SlotNoDisponibleException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class Slot extends Model
{
    use HasFactory;

    /**
     * Nom de la taula
     */
    protected $table = 'slots';

    /**
     * Atributs assignables en massa
     */
    protected $fillable = [
        'data',
        'hora_inici',
        'hora_fi',
        'status',
    ];

    /**
     * Casts automàtics
     */
    protected function casts(): array
    {
        return [
            'data' => 'date',
            'status' => SlotStatus::class,
        ];
    }

    // ==================== RELACIONS ====================

    /**
     * Relació amb la reserva que ocupa aquest slot
     * Un slot només pot tenir una reserva aprovada
     */
    public function reserva(): HasOne
    {
        return $this->hasOne(Reserva::class, 'id_slot');
    }

    /**
     * Només la reserva aprovada d'aquest slot (si existeix)
     */
    public function reservaAprovada(): HasOne
    {
        return $this->reserva()->where('status', EstatReserva::APROVAT);
    }

    // ==================== SCOPES ====================

    /**
     * Només slots disponibles per reservar
     */
    public function scopeDisponibles($query)
    {
        return $query->where('status', SlotStatus::DISPONIBLE);
    }

    /**
     * Només slots de dates futures
     */
    public function scopeFuturs($query)
    {
        return $query->where('data', '>=', Carbon::today());
    }

    /**
     * Slots disponibles i futurs (combinació comuna)
     */
    public function scopeReservables($query)
    {
        return $query->disponibles()->futurs();
    }

    /**
     * Slots ordenats per data i hora
     */
    public function scopeOrdenatsPerData($query)
    {
        return $query->orderBy('data', 'asc')
                    ->orderBy('hora_inici', 'asc');
    }

    // ==================== MÈTODES DE NEGOCI ====================

    /**
     * Verificar si aquest slot està disponible per reservar
     * 
     * @return bool
     */
    public function isAvailable(): bool
    {
        // Verificar estat
        if (!$this->status->isBookable()) {
            return false;
        }

        // Verificar que no sigui en el passat
        if ($this->data->isPast()) {
            return false;
        }

        // Verificar que no tingui cap reserva APROVADA
        if ($this->reservaAprovada()->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Marcar slot com a reservat
     * Fail-fast: llença excepció si no es pot reservar
     * 
     * @throws SlotNoDisponibleException
     * @return void
     */
    public function marcarComReservat(): void
    {
        // Validar que el slot estigui disponible
        if ($this->status !== SlotStatus::DISPONIBLE) {
            throw SlotNoDisponibleException::noReservable(
                $this->id,
                $this->status->value
            );
        }

        // Validar que no sigui en el passat
        if ($this->data->isPast()) {
            throw SlotNoDisponibleException::enElPassat(
                $this->id,
                $this->data->format('Y-m-d')
            );
        }

        // Validar que no tingui ja una reserva APROVADA
        if ($this->reservaAprovada()->exists()) {
            throw SlotNoDisponibleException::jaReservat($this->id);
        }

        // Canviar estat
        $this->status = SlotStatus::RESERVAT;
        $this->save();
    }

    /**
     * Alliberar slot (tornar a disponible)
     * 
     * @return void
     */
    public function alliberar(): void
    {
        // Només es pot alliberar si està reservat
        if ($this->status !== SlotStatus::RESERVAT) {
            return;
        }

        $this->status = SlotStatus::DISPONIBLE;
        $this->save();
    }

    /**
     * Bloquejar slot (per manteniment, etc)
     * 
     * @return void
     */
    public function bloquejar(): void
    {
        $this->status = SlotStatus::BLOQUEJAT;
        $this->save();
    }

    // ==================== HELPERS ====================

    /**
     * Obtenir el text formatejat de la data i hora
     * 
     * @return string
     */
    public function getDataHoraFormattedAttribute(): string
    {
        return $this->data->format('d/m/Y') . ' ' . 
               substr($this->hora_inici, 0, 5) . ' - ' . 
               substr($this->hora_fi, 0, 5);
    }

    /**
     * Verificar si el slot és avui
     * 
     * @return bool
     */
    public function isToday(): bool
    {
        return $this->data->isToday();
    }

    /**
     * Verificar si el slot és demà
     * 
     * @return bool
     */
    public function isDema(): bool
    {
        return $this->data->isTomorrow();
    }

    /**
     * Obtenir els dies restants fins al slot
     * 
     * @return int
     */
    public function diesRestants(): int
    {
        return Carbon::today()->diffInDays($this->data, false);
    }
}