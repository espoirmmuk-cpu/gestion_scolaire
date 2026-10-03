<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sync_device_cursors', function (Blueprint $table) {
            $table->id();

            $table->uuid('device_id')->unique();

            $table->unsignedBigInteger('last_operation_id')->default(0);

            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('last_operation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sync_device_cursors');
    }
};