<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('text', 500);
            $table->foreignId('recepinet_id')->constrained('users');
            $table->string('transport');
            $table->string('status');
            $table->timestamps();

            $table->index(['recepinet_id', 'status']);
            $table->index(['recepinet_id', 'transport']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
