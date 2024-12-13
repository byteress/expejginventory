<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();

            $table->uuid('delivery_id');
            $table->uuid('driver');
            $table->string('truck');
            $table->uuid('branch_id');
            $table->text('notes')->nullable();
            $table->integer('status');

            $table->timestamp('assigned_at');
            $table->timestamp('completed_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
