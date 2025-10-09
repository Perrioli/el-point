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
        Schema::create('agregado_producto', function (Blueprint $table) {
            $table->primary(['agregado_id', 'producto_id']);
            $table->foreignId('agregado_id')->constrained('agregados', 'id_agregado')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos', 'id_producto')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agregado_producto');
    }
};
