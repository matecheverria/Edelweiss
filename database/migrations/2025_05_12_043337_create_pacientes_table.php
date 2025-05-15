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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_apellidos');
            $table->integer('edad')->nullable();
            $table->float('peso')->nullable();
            $table->float('talla')->nullable();
            $table->string('celular')->nullable();
            $table->boolean('enfermedad_diabetes')->default(false);
            $table->boolean('enfermedad_hipertension')->default(false);
            $table->boolean('enfermedad_marcapaso')->default(false);
            $table->boolean('enfermedad_corazon')->default(false);
            $table->boolean('usa_anticoagulantes')->default(false);
            $table->boolean('artritis_osteoporosis')->default(false);
            $table->boolean('usa_protesis')->default(false);
            $table->text('detalle_protesis')->nullable(); // Para el detalle de la prótesis
            $table->text('otras_enfermedades')->nullable();
            $table->text('operaciones')->nullable();
            $table->text('alergico_a')->nullable();
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
// **Descripción de la migración:**
// Esta migración crea la tabla `pacientes` con los siguientes campos:
// - `id`: Identificador único para cada paciente.
// - `nombre_apellidos`: Nombre y apellidos del paciente.
// - `edad`: Edad del paciente.
// - `peso`: Peso del paciente.
// - `talla`: Talla del paciente.
// - `celular`: Número de celular del paciente.
// - `enfermedad_diabetes`: Indica si el paciente tiene diabetes (booleano).              

