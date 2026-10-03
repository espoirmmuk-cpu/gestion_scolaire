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
        Schema::create('sync_logs', function (Blueprint $table) {
            $table->id();

            $table->enum('direction', [
                'local_to_cloud',
                'cloud_to_local',
            ]);

            $table->string('table_name')->nullable();
            $table->uuid('record_uuid')->nullable();

            $table->integer('operations_count')->default(0);

            $table->enum('status', [
                'started',
                'success',
                'partial',
                'failed',
            ])->default('started');

            $table->text('message')->nullable();

            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('finished_at')->nullable();

            $table->index(['direction', 'status']);
            $table->index('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_logs');
    }
};
