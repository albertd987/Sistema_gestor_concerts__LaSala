<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [ //llista de camps q es poden assignar amb user::create([...])
        'name',
        'email',
        'password',
        'rol',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'rol'=>UserRole::class // Castejar automaticament cap a enum
        ];
    }
    //RELACIONS
    public function artista(): HasOne{
        return $this->hasOne(Artista::class,'id_usuari');
    }

    //HELPERS
    public function isAdmin():bool{
        return $this->rol->isAdmin();
    }
    public function isArtista():bool{
        return $this->rol->isArtista();
    }
    public function isPublic():bool{
        return $this->rol->isPublic();
    }
    public function ObtenirNom(): string{
        return $this->name ?: $this->email;
    }
}
