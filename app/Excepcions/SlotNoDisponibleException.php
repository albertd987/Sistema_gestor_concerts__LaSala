<?php


namespace App\Excepcions;

use Exception;

class SlotNoDisponibleException extends Exception
{
    /**
     * Crear excepció per slot ja reservat
     * 
     * @param int $slotId
     * @return self
     */
    public static function jaReservat(int $slotId): self
    {
        return new self(
            "El slot #{$slotId} ja està reservat"
        );
    }
    
    /**
     * Crear excepció per slot no reservable
     * 
     * @param int $slotId
     * @param string $status Estat actual del slot
     * @return self
     */
    public static function noReservable(int $slotId, string $status): self
    {
        return new self(
            "El slot #{$slotId} està '{$status}' i no es pot reservar"
        );
    }
    
    /**
     * Crear excepció per slot en el passat
     * 
     * @param int $slotId
     * @param string $data Data del slot
     * @return self
     */
    public static function enElPassat(int $slotId, string $data): self
    {
        return new self(
            "El slot #{$slotId} ({$data}) ja ha passat i no es pot reservar"
        );
    }
}