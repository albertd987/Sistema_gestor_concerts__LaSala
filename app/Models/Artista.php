<?php

namespace App\Models;

use app\Enums\EstatReserva;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Artista extends Model
{
    use HasFactory;

    /**
     * Nom de la taula
     */
    protected $table = 'artistes';

    /**
     * Atributs assignables en massa
     */
    protected $fillable = [
        'id_usuari',
        'nomGrup',
        'genere',
        'bio',
        'tlf_contacte',
        'links_socials',
    ];

    /**
     * Casts automàtics
     */
    protected function casts(): array
    {
        return [
            'links_socials' => 'array',  // JSON ↔ array
        ];
    }
    
    // ==================== RELACIONS ====================
    
    /**
     * Relació inversa amb User
     */
    public function usuari(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuari');
    }
    
    /**
     * Totes les reserves d'aquest artista
     */
    public function reserves(): HasMany
    {
        return $this->hasMany(Reserva::class, 'id_artista');
    }
    
    /**
     * Només reserves aprovades
     */
    public function reservesAprovades(): HasMany
    {
        return $this->reserves()
                    ->where('status', EstatReserva::APROVAT);
    }
    
    /**
     * Només reserves pendents
     */
    public function reservesPendents(): HasMany
    {
        return $this->reserves()
                    ->where('status', EstatReserva::PENDENT);
    }
    
    // ==================== MÈTODES HELPER ====================
    
    /**
     * Obtenir l'email de l'artista (des del user)
     */
    public function email(): string
    {
        return $this->usuari->email;
    }
    
    /**
     * ¿Ha tocat alguna vegada a LaSala?
     */
    public function haActuat(): bool
    {
        return $this->reservesAprovades()->exists();
    }
    
    /**
     * Nombre total d'actuacions
     */
    public function totalActuacions(): int
    {
        return $this->reservesAprovades()->count();
    }
    
    /**
     * ¿Té reserves pendents d'aprovar?
     */
    public function tePendents(): bool
    {
        return $this->reservesPendents()->exists();
    }
}