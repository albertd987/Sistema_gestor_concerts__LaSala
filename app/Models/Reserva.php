<?php

namespace App\Models;

use App\Enums\EstatReserva;
use App\Excepcions\InvalidStateTransitionException;
use App\Excepcions\SlotNoDisponibleException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Reserva extends Model
{
    use HasFactory;

    /**
     * Nom de la taula
     */
    protected $table = 'reserves';

    /**
     * Atributs assignables en massa
     */
    protected $fillable = [
        'id_artista',
        'id_slot',
        'status',
        'notes_artistes',
        'notes_admin',
        'aprovat_a',
        'aprovat_per',
    ];

    /**
     * Casts automàtics
     */
    protected function casts(): array
    {
        return [
            'status' => EstatReserva::class,
            'aprovat_a' => 'datetime',
        ];
    }

    // ==================== RELACIONS ====================

    /**
     * Artista que fa la reserva
     */
    public function artista(): BelongsTo
    {
        return $this->belongsTo(Artista::class, 'id_artista');
    }

    /**
     * Slot que es vol reservar
     */
    public function slot(): BelongsTo
    {
        return $this->belongsTo(Slot::class, 'id_slot');
    }

    /**
     * Administrador que va aprovar/rebutjar
     */
    public function aprovador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprovat_per');
    }

    // ==================== SCOPES ====================

    /**
     * Només reserves pendents
     */
    public function scopePendents($query)
    {
        return $query->where('status', EstatReserva::PENDENT);
    }

    /**
     * Només reserves aprovades
     */
    public function scopeAprovades($query)
    {
        return $query->where('status', EstatReserva::APROVAT);
    }

    /**
     * Només reserves rebutjades
     */
    public function scopeRebutjades($query)
    {
        return $query->where('status', EstatReserva::REBUTJAT);
    }

    /**
     * Reserves d'un artista específic
     */
    public function scopeDeArtista($query, int $artistaId)
    {
        return $query->where('id_artista', $artistaId);
    }

    /**
     * Reserves ordenades per data del slot
     */
    public function scopeOrdenadesPorData($query)
    {
        return $query->join('slots', 'reserves.id_slot', '=', 'slots.id')
                    ->orderBy('slots.data', 'desc')
                    ->orderBy('slots.hora_inici', 'desc')
                    ->select('reserves.*');
    }

    // ==================== MÈTODES CRÍTICS DE NEGOCI ====================

    /**
     * Aprovar una reserva
     * Aquest mètode coordina:
     * 1. Validació de precondicions
     * 2. Canvi d'estat de la reserva
     * 3. Canvi d'estat del slot
     * 4. Registre de qui va aprovar i quan
     * 
     * Usa transaccions per garantir consistència
     * 
     * @param User $admin Administrador que aprova
     * @throws InvalidStateTransitionException Si la reserva no es pot aprovar
     * @throws SlotNoDisponibleException Si el slot no està disponible
     * @return void
     */
    public function aprovar(User $admin): void
    {
        // Validar que sigui admin
        if (!$admin->isAdmin()) {
            throw new \InvalidArgumentException('Només els administradors poden aprovar reserves');
        }

        // Validar transició d'estat
        if (!$this->status->potCanviar(EstatReserva::APROVAT)) {
            throw InvalidStateTransitionException::fromTo(
                $this->status->value,
                EstatReserva::APROVAT->value,
                'reserva'
            );
        }

        // Iniciar transacció per garantir consistència
        DB::transaction(function () use ($admin) {
            // Marcar el slot com reservat (fail-fast si no es pot)
            $this->slot->marcarComReservat();

            // Actualitzar la reserva
            $this->status = EstatReserva::APROVAT;
            $this->aprovat_a = now();
            $this->aprovat_per = $admin->id;
            $this->save();
        });
    }

    /**
     * Rebutjar una reserva
     * 
     * @param User $admin Administrador que rebutja
     * @param string $motiu Raó del rebuig
     * @throws InvalidStateTransitionException Si la reserva no es pot rebutjar
     * @return void
     */
    public function rebutjar(User $admin, string $motiu): void
    {
        // Validar que sigui admin
        if (!$admin->isAdmin()) {
            throw new \InvalidArgumentException('Només els administradors poden rebutjar reserves');
        }

        // Validar transició d'estat
        if (!$this->status->potCanviar(EstatReserva::REBUTJAT)) {
            throw InvalidStateTransitionException::fromTo(
                $this->status->value,
                EstatReserva::REBUTJAT->value,
                'reserva'
            );
        }

        // Actualitzar la reserva
        $this->status = EstatReserva::REBUTJAT;
        $this->notes_admin = $motiu;
        $this->aprovat_a = now();
        $this->aprovat_per = $admin->id;
        $this->save();

        // El slot es manté disponible (no cal canviar-lo)
    }

    /**
     * Cancel·lar una reserva aprovada (només admin)
     * Aquest és un cas especial que requereix alliberar el slot
     * 
     * @param User $admin
     * @param string $motiu
     * @return void
     */
    public function cancelar(User $admin, string $motiu): void
    {
        // Només es poden cancel·lar reserves aprovades
        if ($this->status !== EstatReserva::APROVAT) {
            throw new \InvalidArgumentException('Només es poden cancel·lar reserves aprovades');
        }

        if (!$admin->isAdmin()) {
            throw new \InvalidArgumentException('Només els administradors poden cancel·lar reserves');
        }

        DB::transaction(function () use ($admin, $motiu) {
            // Alliberar el slot
            $this->slot->alliberar();

            // Marcar com rebutjada amb nota especial
            $this->status = EstatReserva::REBUTJAT;
            $this->notes_admin = "CANCEL·LACIÓ: " . $motiu;
            $this->aprovat_per = $admin->id;
            $this->save();
        });
    }

    // ==================== HELPERS ====================

    /**
     * Obtenir el nom de l'artista
     */
    public function getNomArtistaAttribute(): string
    {
        return $this->artista->nomGrup;
    }

    /**
     * Obtenir la data del slot formatejada
     */
    public function getDataSlotFormattedAttribute(): string
    {
        return $this->slot->getDataHoraFormattedAttribute();
    }

    /**
     * Verificar si la reserva és visible públicament
     */
    public function isPublic(): bool
    {
        return $this->status->esVisible();
    }

    /**
     * Verificar si l'artista pot editar la reserva
     */
    public function potEditar(): bool
    {
        return $this->status->esPendent();
    }

    /**
     * Obtenir el color de l'estat per la UI
     */
    public function getStatusColorAttribute(): string
    {
        return $this->status->color();
    }

    /**
     * Obtenir la icona de l'estat per la UI
     */
    public function getStatusIconAttribute(): string
    {
        return $this->status->icon();
    }

    /**
     * Obtenir el label de l'estat en català
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    /**
     * Verificar si han passat més de X dies des de la sol·licitud
     */
    public function diesDesDeSolicitud(): int
    {
        return $this->created_at->diffInDays(now());
    }

    /**
     * Verificar si la reserva està pendent de revisió fa massa temps
     */
    public function esUrgent(): bool
    {
        return $this->status->esPendent() && $this->diesDesDeSolicitud() > 3;
    }
}