<?php 

namespace App\Enums;

enum UserRole: string {
    case PUBLIC = 'public';
    case ARTISTA = 'artista';
    case ADMIN = 'admin';

    public function label(): string {
        return match ($this) {
            self::PUBLIC => 'Visitant',
            self::ARTISTA => 'Artista',
            self::ADMIN => 'Administrador',
        };
    }

    public function isAdmin(): bool {
        return $this === self::ADMIN;
    }

    public function isArtista(): bool {
        return $this === self::ARTISTA;
    }
    public function isPublic(): bool {
        return $this === self::PUBLIC;
    }

    public function ColorUI(): string {
        return match ($this) {
            self::PUBLIC=>'gray',
            self::ARTISTA=>'purple',
            self::ADMIN=>'red',
        };
    }
}