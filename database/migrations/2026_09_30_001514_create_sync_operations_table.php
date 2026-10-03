<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sync_operations', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid_operation')->unique();

            $table->string('table_name', 100);
            $table->uuid('record_uuid');

            $table->enum('operation', [
                'create',
                'update',
                'delete',
            ]);

            $table->json('payload')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('synced_at')->nullable();

            $table->enum('status', [
                'pending',
                'synced',
                'failed',
            ])->default('pending');

            $table->text('error_message')->nullable();

            $table->index('record_uuid');
            $table->index(['table_name', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_operations');
    }
};