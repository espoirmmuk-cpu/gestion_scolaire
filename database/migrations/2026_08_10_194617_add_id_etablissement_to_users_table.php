<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id_etablissement')
                ->nullable()
                ->after('id');

            $table->foreign('id_etablissement')
                ->references('id_etablissement')
                ->on('etablissements')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_etablissement']);
            $table->dropColumn('id_etablissement');
        });
    }
};
