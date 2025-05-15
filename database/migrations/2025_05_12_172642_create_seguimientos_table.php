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
        Schema::create('seguimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained()->onDelete('cascade'); // Clave foránea al paciente
            $table->date('fecha'); // Fecha de la cita de seguimiento
            $table->text('examenes_auxiliares')->nullable(); // Detalle de exámenes para esta cita
            $table->text('diagnostico')->nullable(); // Diagnóstico de esta cita
            $table->text('tratamiento_adecuado')->nullable(); // Tratamiento de esta cita
            $table->text('indicaciones')->nullable(); // Indicaciones de esta cita
            $table->date('proxima_cita')->nullable(); // Fecha de la próxima cita

            // Puedes añadir campos adicionales si la sección EVOLUCIÓN es diferente de los detalles del examen
            $table->text('evolucion_texto')->nullable(); // Texto de la evolución para esta cita

            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimientos');
    }
};