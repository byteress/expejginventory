<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transfer_items', function (Blueprint $table) {
            $table->uuid('transfer_id');
            $table->uuid('product_id');
            $table->integer('transferred');
            $table->integer('received')->default(0);
            $table->integer('damaged')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfer_items');
    }
};
