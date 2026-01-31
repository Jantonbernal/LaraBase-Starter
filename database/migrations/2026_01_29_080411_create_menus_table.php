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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('menu');
            $table->string('hierarchy');
            $table->integer('parent')->nullable();
            $table->unsignedBigInteger('permission_id')->nullable();
            $table->string('icon');
            $table->enum('status', ['1', '0'])->default('1')->comment('1: Activo, 0: Inactivo');
            $table->timestamps();

            $table->foreign('permission_id')->references('id')->on('permissions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
