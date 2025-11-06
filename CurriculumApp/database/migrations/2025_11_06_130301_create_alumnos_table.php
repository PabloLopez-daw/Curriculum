<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('telefono')->nullable();
            $table->string('correo')->unique();
            $table->date('fecha_nacimiento')->nullable();
            $table->decimal('nota_media', 4, 2)->nullable(); // ej. 9.50
            $table->text('experiencia')->nullable();
            $table->text('formacion')->nullable();
            $table->text('habilidades')->nullable();
            $table->string('fotografia')->nullable(); // ruta imagen (public)
            $table->string('curriculum_path_private')->nullable(); // ruta en storage local (privado)
            $table->string('curriculum_path_public')->nullable();  // ruta en storage public (storage/app/public)
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
