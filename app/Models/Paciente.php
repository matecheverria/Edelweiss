<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre_apellidos',
        'edad',
        'peso',
        'talla',
        'celular',
        'enfermedad_diabetes',
        'enfermedad_hipertension',
        'enfermedad_marcapaso',
        'enfermedad_corazon',
        'usa_anticoagulantes',
        'artritis_osteoporosis',
        'usa_protesis',
        'detalle_protesis',
        'otras_enfermedades',
        'operaciones',
        'alergico_a',
        // >>> Añadir las columnas del Paso 2 aquí <<<
        'eval_piel_varices',
        'eval_piel_trombosis',
        'eval_piel_edema',
        'eval_piel_resequedad',
        'eval_piel_otros',
        'micosis_interdigital', // Si guardas como JSON
        'hiperqueratosis_derecho',
        'hiperqueratosis_izquierdo',
        'callos', // Si guardas como JSON
        'otras_alteraciones_pie', // Si guardas como JSON
        'alteraciones_dedos_especificas', // Si guardas como JSON
        'alteraciones_estaticas', // Si guardas como JSON

        // >>> Añadir las columnas del Paso 3 aquí <<<
        'examenes_auxiliares_texto', // Nombre de la columna en BD
        'examen_fecha',
        'examen_diagnostico',
        'examen_tratamiento_adecuado',
        'examen_indicaciones',
        'examen_proxima_cita',
        'evolucion_fecha',
        'evolucion_texto', // Nombre de la columna en BD
        'epicrisis',
        'photos',
    ];
    public function seguimientos()
    {
        return $this->hasMany(Seguimiento::class);
    }
    protected $casts = [
    // ... otros casts
    'photos' => 'array',
];

    // Opcional: Si prefieres proteger campos en lugar de listar los permitidos (menos común)
    // protected $guarded = []; // Esto permitiría la asignación masiva de *todos* los campos, úsalo con precaución

}