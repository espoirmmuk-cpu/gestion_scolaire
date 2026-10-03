<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->uuid('uuid_sync')->nullable()->unique()->after('id_role');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->uuid('uuid_sync')->nullable()->unique()->after('id_permission');
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique(['uuid_sync']);
            $table->dropColumn('uuid_sync');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropUnique(['uuid_sync']);
            $table->dropColumn('uuid_sync');
        });
    }
};