<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * No cal guardar la data i la hora aqui, ja que id_slot ja apunta a la taula slots
     */
    public function up(): void
    {
        Schema::create('reserves',function(Blueprint $table){
            $table->id();

            //Relacions
            $table->foreignId('id_artista')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('id_slot')
                ->constrained()
                ->cascadeOnDelete();

            //Estat de la reserva
            $table->enum('status',['pendent','aprovat','rebutjat'])
                 ->default('pendent');
            $table->text('notes_artistes')->nullable(); //Comentari artista
            $table->text('notes_admin')->nullable(); //Resposta admin

            //Metadates d'aprovació
            $table->timestamp('aprovat_a')->nullable();
            $table->foreignId('aprovat_per')
                ->nullable()
                ->constrained('usuaris')
                ->nullOnDelete();
            $table->unique(['id_slot','status'],'unique_aprovat_slot');

            //index per consultes frequents
            $table->index(['id_artista','status']);
            $table->index('status');


        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserves');
    }
};
