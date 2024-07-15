<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('advanced_reservations', function (Blueprint $table) {
            $table->id();
            $table->uuid('reservation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advanced_reservations');
    }
};
