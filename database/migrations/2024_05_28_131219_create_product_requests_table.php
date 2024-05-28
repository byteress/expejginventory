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
        Schema::create('product_requests', function (Blueprint $table) {
            $table->uuid('product_id');
            $table->uuid('receiver');
            $table->integer('quantity');
            $table->integer('incoming')->default(0);
            $table->timestamp('date_requested');

            $table->index(['product_id', 'receiver']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_requests');
    }
};
