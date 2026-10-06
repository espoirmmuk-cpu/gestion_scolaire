<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('journaux_activites', function (Blueprint $table) {
            $table->unsignedBigInteger('id_etablissement')
                ->nullable()
                ->after('id_utilisateur')
                ->index();
        });
    }

    public function down(): void
    {
        Schema::table('journaux_activites', function (Blueprint $table) {
            $table->dropColumn('id_etablissement');
        });
    }
};