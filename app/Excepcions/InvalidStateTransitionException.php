<?php

namespace App\Excepcions;

use Exception;

class InvalidStateTransitionException extends Exception
{
    /**
     * Crear excepció per transició d'estat invàlida
     * 
     * @param string $from Estat actual
     * @param string $to Estat desitjat
     * @param string $entity Tipus d'entitat (reserva, slot, etc.)
     * @return self
     */
    public static function fromTo(string $from, string $to, string $entity = 'entitat'): self
    {
        return new self(
            "No es pot transicionar {$entity} de '{$from}' a '{$to}'"
        );
    }
    
    /**
     * Crear excepció per intent de modificar estat final
     * 
     * @param string $currentState
     * @param string $entity
     * @return self
     */
    public static function finalState(string $currentState, string $entity = 'entitat'): self
    {
        return new self(
            "No es pot modificar {$entity}: l'estat '{$currentState}' és final"
        );
    }
}