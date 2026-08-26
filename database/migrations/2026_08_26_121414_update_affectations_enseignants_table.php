<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Finalisation de la table affectations_enseignants.
     */
    public function up(): void
    {
        Schema::table('affectations_enseignants', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | INDEX ÉTABLISSEMENT / ANNÉE SCOLAIRE
            |--------------------------------------------------------------------------
            */

            $table->index(
                ['id_etablissement', 'id_annee_scolaire'],
                'affectation_etab_annee_idx'
            );
        });
    }

    /**
     * Retour arrière.
     */
    public function down(): void
    {
        Schema::table('affectations_enseignants', function (Blueprint $table) {

            $table->dropIndex('affectation_etab_annee_idx');

        });
    }
};