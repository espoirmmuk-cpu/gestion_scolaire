<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eleves', function (Blueprint $table) {
            $table->unsignedBigInteger('id_etablissement')
                ->nullable()
                ->after('id_eleve');

            $table->foreign('id_etablissement')
                ->references('id_etablissement')
                ->on('etablissements')
                ->nullOnDelete();

            $table->index('id_etablissement');
        });
    }

    public function down(): void
    {
        Schema::table('eleves', function (Blueprint $table) {
            $table->dropForeign(['id_etablissement']);
            $table->dropIndex(['id_etablissement']);
            $table->dropColumn('id_etablissement');
        });
    }
};
