<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sync_tombstones', function (Blueprint $table) {
            $table->id();

            $table->string('table_name', 100);
            $table->uuid('record_uuid');

            $table->uuid('device_id')->nullable();

            $table->timestamp('deleted_at')->useCurrent();

            $table->unique(['table_name', 'record_uuid']);

            $table->index('record_uuid');
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sync_tombstones');
    }
};