<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attempt_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('delivery_id');
            $table->uuid('order_id');
            $table->uuid('product_id');
            $table->integer('quantity');
            $table->integer('delivered')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempt_items');
    }
};
