<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Exécuter la migration.
     */
    public function up(): void
    {
        Schema::table('matieres', function (Blueprint $table) {
            $table->unsignedBigInteger('id_etablissement')
                ->nullable()
                ->after('id_matiere');

            $table->foreign('id_etablissement')
                ->references('id_etablissement')
                ->on('etablissements')
                ->restrictOnDelete();
        });

        /*
        |--------------------------------------------------------------------------
        | Rattacher les matières existantes
        |--------------------------------------------------------------------------
        |
        | La matière actuellement présente appartient à
        | "Mon établissement" (id_etablissement = 1).
        |
        */

        DB::table('matieres')
            ->whereNull('id_etablissement')
            ->update([
                'id_etablissement' => 1,
            ]);
    }

    /**
     * Annuler la migration.
     */
    public function down(): void
    {
        Schema::table('matieres', function (Blueprint $table) {
            $table->dropForeign([
                'id_etablissement',
            ]);

            $table->dropColumn('id_etablissement');
        });
    }
};