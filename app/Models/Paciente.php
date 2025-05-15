<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id'; //
    protected $fillable = [
    'nombre_completo',
    'edad',
    'peso',
    'talla',
    'celular',
    'enfermedades',
    'otras_enfermedades',
    'operaciones',
    'alergico_a',
    'antecedentes', // Aquí agregamos la columna antecedentes
];

    // Relación con el modelo Consulta
    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }
    public function evaluacion()
{
    return $this->hasOne(Evaluacion::class);  // Esto supone que tienes una tabla 'evaluaciones'
}

}
