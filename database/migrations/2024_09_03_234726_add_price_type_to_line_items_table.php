<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('line_items', function (Blueprint $table) {
            $table->string('price_type')->after('price')->default('regular_price');
        });
    }

    public function down(): void
    {
        Schema::table('line_items', function (Blueprint $table) {
            $table->dropColumn([
                'price_type',
            ]);
        });
    }
};
