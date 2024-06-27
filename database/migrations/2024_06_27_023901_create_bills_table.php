<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('installment_bills', function (Blueprint $table) {
            $table->uuid('order_id');
            $table->uuid('installment_id');
            $table->uuid('customer_id');
            $table->integer('index');
            $table->date('due');
            $table->integer('penalty');
            $table->integer('balance');

            $table->primary(['order_id', 'customer_id', 'installment_id', 'index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
