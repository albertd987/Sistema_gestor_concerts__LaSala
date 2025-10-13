<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * En aquesta taula hi ha informació específica dels artistes, va separada de la taula d'usuaris ja que no tots els usuaris son artistes
     * i de guardarlos tots en una mateixa taula hi haurien molts camps que serien NULL, també queda més net a l'hora de fer consultes
     */
    public function up(): void
    {
        Schema::create('artistes',function(Blueprint $table){
            $table->id();

            //Relació 1:1 amb usuaris
            $table->foreignId('id_usuari')
                ->constrained()
                ->cascadeOnDelete();

            //Info bàsica de l'artista
            $table->string('nomGrup');
            $table->string('genere')->nullable();
            $table->text('bio')->nullable();
            $table->string('tlf_contacte',20)->nullable();

            //Links a xarxes socials(ho guardem com a JSON)
            $table->json('links_socials')->nullable();
            $table->timestamps();

            //Un usuari només pot ser un artista, per això afegim aquesta restricció 
            $table->unique('id_usuari');
            

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artistes');
    }
};
