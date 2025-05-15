<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Paciente; // Importa el modelo Paciente
use Livewire\WithPagination; // Opcional: para paginación si tienes muchos pacientes

class ListPatients extends Component
{
    // Opcional: Usa paginación si esperas muchos registros
    // use WithPagination;

    // Propiedad para buscar pacientes (opcional)
    // public $search = '';

    public function render()
    {
        // Obtener todos los pacientes
        // Si usas paginación: Paciente::latest()->paginate(10)
        $pacientes = Paciente::latest()->get(); // latest() ordena por created_at descendente

        // Si usas búsqueda:
        // $pacientes = Paciente::latest()
        //     ->where('nombre_apellidos', 'like', '%' . $this->search . '%')
        //     ->paginate(10);

        return view('livewire.list-patients', [
            'pacientes' => $pacientes,
        ]);
    }

    // Opcional: Método para resetear la paginación al buscar
    // public function updatingSearch()
    // {
    //     $this->resetPage();
    // }
}