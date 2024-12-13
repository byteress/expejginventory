<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('transfer_number');
            $table->uuid('receiver_branch');
            $table->uuid('sender_branch');
            $table->uuid('driver');
            $table->string('truck');
            $table->uuid('requested');
            $table->text('notes')->nullable();
            $table->integer('status');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
