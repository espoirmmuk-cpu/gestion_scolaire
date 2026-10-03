<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('utilisateurs_roles', function (Blueprint $table) {
            $table->uuid('uuid_sync')->nullable()->unique()->after('id_role');
        });
    }

    public function down(): void
    {
        Schema::table('utilisateurs_roles', function (Blueprint $table) {
            $table->dropUnique(['uuid_sync']);
            $table->dropColumn('uuid_sync');
        });
    }
};