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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('business_name')->unique()->comment('Razón social de la empresa');
            $table->string('trade_name')->unique()->comment('Nombre comercial de la empresa');
            $table->string('document')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('phone_number')->nullable();
            $table->unsignedBigInteger('file_id')->nullable();
            $table->timestamps();

            $table->foreign('file_id')->references('id')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
