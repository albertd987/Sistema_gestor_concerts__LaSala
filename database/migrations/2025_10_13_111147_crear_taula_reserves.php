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
                ->constrained('artistes') //algunes taules les he d'especificar xq laravel ho singularitza
                ->cascadeOnDelete();
            $table->foreignId('id_slot')
                ->constrained('slots') //algunes taules les he d'especificar xq laravel ho singularitza
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
                ->constrained('users')
                ->nullOnDelete();
            $table->unique(['id_slot','status'],'unique_aprovat_slot');

            //index per consultes frequents
            $table->index(['id_artista','status']);
            $table->index('status');
            $table->timestamps();

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
