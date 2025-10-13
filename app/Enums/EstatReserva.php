<?php 

namespace App\Enums;

enum EstatReserva: string {
    case PENDENT = 'pendent';
    case APROVAT = 'aprovat';
    case REBUTJAT = 'rebutjat';

    public function label(): string {
        return match ($this) {
            self::PENDENT => 'Pendent',
            self::APROVAT => 'Aprovat',
            self::REBUTJAT => 'Rebutjat',
        };
    }
    public function color(): string {
        return match ($this) {
            self::PENDENT=>'yellow',
            self::APROVAT=>'green',
            self::REBUTJAT=>'red',
        };
    }

    public function icon():string{
        return match ($this) {
            self::PENDENT=>'clock',
            self::APROVAT=>'check-circle',
            self::REBUTJAT=>'x-circle',
        };
    }

    public function potCanviar(self $nouEstat):bool{
        return match ($this){
            self::PENDENT=>in_array($nouEstat,[self::APROVAT,self::REBUTJAT]),
            self::APROVAT=>false,
            self::REBUTJAT=>false,
            };
        }

    public function esFinal():bool{
        return $this!==self::PENDENT;
    }

    public function esVisible():bool{
        return $this===self::APROVAT;

    }

    public function esPendent():bool{
        return $this===self::PENDENT;
    }
    public function esAprovada():bool{
        return $this===self::APROVAT;

    }
    public function esRebutjada():bool{
        return $this===self::REBUTJAT;
    }
}