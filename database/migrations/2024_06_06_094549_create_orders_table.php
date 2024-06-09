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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('order_id');
            $table->uuid('branch_id');
            $table->uuid('customer_id');
            $table->uuid('assistant_id');
            $table->integer('total');
            $table->boolean('requires_authorization')->default(false);
            $table->timestamp('placed_at');
            $table->timestamp('completed_at')->nullable();
            $table->string('receipt_number')->nullable();
            $table->uuid('cashier')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
