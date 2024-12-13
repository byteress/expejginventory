<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('delivery_items', function (Blueprint $table) {
            $table->uuid('product_id');
            $table->uuid('order_id');
            $table->string('title');
            $table->integer('quantity');
            $table->string('reservation_id');
            $table->integer('to_ship')->default(0);
            $table->integer('out_for_delivery')->default(0);
            $table->integer('delivered')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_items');
    }
};
