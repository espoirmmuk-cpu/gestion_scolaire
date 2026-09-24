<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter la migration.
     */
    public function up(): void
    {
        Schema::table('annees_scolaires', function (Blueprint $table) {

            $table->boolean('est_cloturee')
                ->default(false)
                ->after('est_active');

            $table->timestamp('date_cloture')
                ->nullable()
                ->after('est_cloturee');

            $table->unsignedBigInteger('cloturee_par')
                ->nullable()
                ->after('date_cloture');
        });
    }

    /**
     * Annuler la migration.
     */
    public function down(): void
    {
        Schema::table('annees_scolaires', function (Blueprint $table) {

            $table->dropColumn([
                'est_cloturee',
                'date_cloture',
                'cloturee_par',
            ]);
        });
    }
};