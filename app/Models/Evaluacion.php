<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;

    // Definir los campos que pueden ser asignados masivamente
    protected $table = 'evaluaciones'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id'; //
    protected $fillable = [
        'paciente_id', 
        'varices', 
        'trombosis', 
        'edema', 
        'resequedad', 
        'hiperqueratosis', 
        'callos', 
        'plantar', 
        'metatarso', 
        'agrandamiento_talon', 
        'forzado', 
        'semilunar', 
        'interdigital', 
        'miliar', 
        'heloma', 
        'subungueal', 
        'otros',
        'fecha_control'
        
    ];

    // Relación con el modelo Paciente (uno a uno)
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);  // Relación inversa (Evaluacion pertenece a Paciente)
    }
}
