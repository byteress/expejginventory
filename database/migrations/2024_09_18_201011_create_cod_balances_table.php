<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cod_balances', function (Blueprint $table) {
            $table->id();

            $table->uuid('order_id');
            $table->integer('amount');
            $table->integer('balance');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cod_balances');
    }
};
