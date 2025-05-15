<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    use HasFactory;

    // Define los campos que pueden ser asignados masivamente para Seguimiento
    protected $fillable = [
        'paciente_id',
        'fecha',
        'examenes_auxiliares',
        'diagnostico',
        'tratamiento_adecuado',
        'indicaciones',
        'proxima_cita',
        'evolucion_texto',
    ];


    /**
     * Get the paciente that owns the seguimiento.
     */
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}