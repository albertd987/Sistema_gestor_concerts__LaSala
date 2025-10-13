<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * He separat datetime en date i time perquè em donava problemes probant casos on el concert començava a les 22:00 d'un dia i acaba a la 01:00 del dia seguent
     * 
     * Els estats de cada eslot seran sempre disponibles, reservats o bloquejats
     *      Disponible-> Admin crea l'slot, queda disponible per reservar
     *      Reservat-> S'aprova una reserva
     *      Bloquejat-> Reserva bloquejada (Només possible per l'admin, en cas de manteniment o el que sigui)
     * 
     * Aquest sistema és millor que un true/false, ja que permet tenir més control sobre com està realment cada slot 
     */
    public function up(): void
    {
        Schema::create('slots',function(Blueprint $table){
            $table->id();

            //Data i horari de l'slot
            $table->date('data');
            $table->time('hora_inici');
            $table->time('hora_fi');

            //Estat del slot
            $table->enum('status',['disponible','reservat','bloquejat'])
            ->default('disponible');
            $table->timestamps();

            //Prevenir slots duplicats amb una clau composta
            /**
             * INSERT INTO slots (data, hora_inici, hora_fi) VALUES ('2024-10-15', '22:00', '23:30'); ->aquest funcionarà, insert correcte
             * INSERT INTO slots (data, hora_inici, hora_fi) VALUES ('2024-10-15', '22:00', '23:30'); -> Aquest fallarà perquè salta la restricció per clau composta ('ERROR: DUPLICATE ENTRY')
             */
            $table->unique(['data','hora_inici','hora_fi']);


            //índex x cerques frequents
            $table->index(['data','status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slots');
    }
};
