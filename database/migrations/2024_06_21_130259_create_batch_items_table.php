<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('batch_items', function (Blueprint $table) {
            $table->uuid('batch_id');
            $table->uuid('product_id');
            $table->integer('quantity');

            $table->index(['batch_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_items');
    }
};
