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
        Schema::create('receive_history', function (Blueprint $table) {
            $table->uuid('product_id');
            $table->uuid('branch_id');
            $table->integer('quantity');
            $table->uuid('user_id');
            $table->timestamp('date');

            $table->index(['product_id', 'branch_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receive_history');
    }
};
