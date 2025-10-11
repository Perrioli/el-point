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
        Schema::table('users', function (Blueprint $table) {
            // Renombramos 'name' a 'username'
            $table->renameColumn('name', 'username');

            // Agregamos el nombre completo DESPUÉS de 'username'
            $table->string('nombre_completo')->after('username');

            // Agregamos el rol
            $table->string('rol')->default('Mozo')->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
