<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajouter module et action à la table permissions.
     */
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('module', 100)
                ->nullable()
                ->after('id_permission');

            $table->string('action', 100)
                ->nullable()
                ->after('module');

            $table->index(['module', 'action']);
        });
    }

    /**
     * Annuler la modification.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropIndex([
                'permissions_module_action_index',
            ]);

            $table->dropColumn([
                'module',
                'action',
            ]);
        });
    }
};