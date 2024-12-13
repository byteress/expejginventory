<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('customer_balances', function (Blueprint $table) {
            $table->integer('installment')->default(0);
            $table->integer('cod')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('customer_balances', function (Blueprint $table) {
            $table->dropColumn(['installment', 'cod']);
        });
    }
};
