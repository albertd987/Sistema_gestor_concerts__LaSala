<?php 

namespace App\Enums;

enum SlotStatus: string {
    case DISPONIBLE = 'disponible';
    case RESERVAT = 'reservat';
    case BLOQUEJAT = 'bloquejat';

    public function label(): string {
        return match ($this) {
            self::DISPONIBLE => 'Disponible',
            self::RESERVAT => 'Reservat',
            self::BLOQUEJAT => 'Bloquejat',
        };
    }

    public function color(): string {
        return match ($this) {
            self::DISPONIBLE=>'green',
            self::RESERVAT=>'red',
            self::BLOQUEJAT=>'gray',
        };

    }
    public function icon(): string {
        return match ($this) {
            self::DISPONIBLE=>'check-circle',
            self::RESERVAT=>'x-circle',
            self::BLOQUEJAT=>'lock-closed',
        };
    }
    public function isBookable(): bool {
        return $this === self::DISPONIBLE;
    }

    public function isOccupied(): bool {
        return $this === self::RESERVAT;
    }
}