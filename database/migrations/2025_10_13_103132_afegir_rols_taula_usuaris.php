<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration //crear la taula usuaris abans de migrar
{
    /**
     * Run the migrations.
     * No cal fer una taula separada de rols, ja que els rols que tenim (públic, artista, admin) mai canviaran, així 
     * que es fa amb un enum, i de pas ens estalviem fer JOINs extres 
     * email->rol->pass
     */
    public function up(): void
    {
        Schema::table('usuaris', function (Blueprint $table) {
            $table->enum('rol',['public','artista','admin'])
                ->default('public') 
                ->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuaris', function (Blueprint $table) {
            $table->dropColumn('rol');
        });
    }
};
