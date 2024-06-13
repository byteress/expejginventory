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
        Schema::create('stock_history', function (Blueprint $table) {
            $table->uuid('product_id');
            $table->uuid('branch_id');
            $table->integer('quantity');
            $table->uuid('user_id');
            $table->string('action');
            $table->integer('running_available');
            $table->integer('running_reserved');
            $table->integer('running_damaged');
            $table->integer('version');
            $table->timestamp('date');

            $table->index(['product_id', 'branch_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_history');
    }
};
