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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('path')->comment('Ruta de almacenamiento en disco');
            $table->string('name')->comment('Nombre amigable u original');
            $table->string('mime_type')->comment('Tipo MIME del archivo');
            $table->unsignedBigInteger('uploaded_by')->nullable()->comment('ID del usuario que subió el archivo');
            $table->enum('status', ['1', '0'])->default('1')->comment('Borrado lógico');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
