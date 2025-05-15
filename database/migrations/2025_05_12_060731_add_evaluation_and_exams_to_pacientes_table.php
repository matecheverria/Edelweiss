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
        Schema::table('pacientes', function (Blueprint $table) {
            // Columnas del Paso 2
            $table->boolean('eval_piel_varices')->default(false)->nullable();
            $table->boolean('eval_piel_trombosis')->default(false)->nullable();
            $table->boolean('eval_piel_edema')->default(false)->nullable();
            $table->boolean('eval_piel_resequedad')->default(false)->nullable();
            $table->text('eval_piel_otros')->nullable(); // Para el campo de texto "OTROS"

            // Para los arrays que guardamos como JSON
            $table->json('micosis_interdigital')->nullable();
            $table->text('hiperqueratosis_derecho')->nullable();
            $table->text('hiperqueratosis_izquierdo')->nullable();
            $table->json('callos')->nullable();
            $table->json('otras_alteraciones_pie')->nullable();
            $table->json('alteraciones_dedos_especificas')->nullable();
            $table->json('alteraciones_estaticas')->nullable();


            // Columnas del Paso 3
            $table->text('examenes_auxiliares_texto')->nullable(); // Texto de Exámenes Auxiliares
            $table->date('examen_fecha')->nullable(); // Fecha del Examen Auxiliar
            $table->text('examen_diagnostico')->nullable();
            $table->text('examen_tratamiento_adecuado')->nullable();
            $table->text('examen_indicaciones')->nullable();
            $table->date('examen_proxima_cita')->nullable(); // Fecha de Próxima Cita

            $table->date('evolucion_fecha')->nullable(); // Fecha de Evolución
            $table->text('evolucion_texto')->nullable(); // Texto de Evolución

            $table->text('epicrisis')->nullable(); // Texto de Epicrisis

            // NOTA: Si decidiste usar tablas separadas para Evaluaciones, Exámenes, etc.,
            // la migración sería diferente (crear esas tablas y añadir la clave foránea `paciente_id`).
            // Este ejemplo asume que añades todas las columnas a la tabla `pacientes`.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            // Define cómo revertir los cambios (eliminar las columnas)
            $table->dropColumn([
                'eval_piel_varices',
                'eval_piel_trombosis',
                'eval_piel_edema',
                'eval_piel_resequedad',
                'eval_piel_otros',
                'micosis_interdigital',
                'hiperqueratosis_derecho',
                'hiperqueratosis_izquierdo',
                'callos',
                'otras_alteraciones_pie',
                'alteraciones_dedos_especificas',
                'alteraciones_estaticas',
                'examenes_auxiliares_texto',
                'examen_fecha',
                'examen_diagnostico',
                'examen_tratamiento_adecuado',
                'examen_indicaciones',
                'examen_proxima_cita',
                'evolucion_fecha',
                'evolucion_texto',
                'epicrisis',
            ]);
        });
    }
};