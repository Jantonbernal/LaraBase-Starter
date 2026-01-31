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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')            // usuario que provoca el evento
                ->nullable()->constrained('users')->nullOnDelete();
            $table->string('route')->nullable();    // endpoint o proceso
            $table->string('method')->nullable();   // verbo HTTP o comando
            $table->string('message');              // resumen del evento
            $table->json('payload')->nullable();    // datos adicionales (request, cambios…)
            $table->enum('status', ['success', 'error'])->default('success');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
