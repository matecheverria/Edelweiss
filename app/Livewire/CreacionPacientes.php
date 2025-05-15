<?php

namespace App\Livewire;

use Livewire\Component;

class CreacionPacientes extends Component
{
    // Propiedades para bindeo con el formulario
    public $nombre_completo = '';
    public $edad;
    public $peso;
    public $talla;
    public $celular = '';
    public $enfermedades = []; // Para las casillas de verificación
    public $otras_enfermedades = '';
    public $operaciones = '';
    public $alergico_a = '';

    // Reglas de validación
    protected $rules = [
        'nombre_completo' => 'required|string|max:255',
        'edad' => 'nullable|integer|min:0',
        'peso' => 'nullable|numeric|min:0',
        'talla' => 'nullable|numeric|min:0',
        'celular' => 'nullable|string|max:20',
        'enfermedades' => 'nullable|array',
        'otras_enfermedades' => 'nullable|string|max:1000',
        'operaciones' => 'nullable|string|max:1000',
        'alergico_a' => 'nullable|string|max:255',
    ];

    // Método que se ejecuta al enviar el formulario
    public function savePatient()
    {
 
        $this->validate();
  
        try {
            Paciente::create([
                'nombre_completo' => $this->nombre_completo,
                'edad' => $this->edad,
                'peso' => $this->peso,
                'talla' => $this->talla,
                'celular' => $this->celular,
                'enfermedades' => json_encode($this->enfermedades), // Guarda como JSON si es necesario
                'otras_enfermedades' => $this->otras_enfermedades,
                'operaciones' => $this->operaciones,
                'alergico_a' => $this->alergico_a,
                // Añade otros campos si tu modelo Paciente los tiene
            ]);

            // Limpiar formulario después de guardar (opcional)
            $this->reset();

            // Emitir evento o redirigir (ejemplo: redirigir a la lista de pacientes)
            session()->flash('message', 'Paciente registrado exitosamente.');
            return redirect()->to('/pacientes'); // Ajusta la ruta según tu aplicación

        } catch (\Exception $e) {
            // Manejo básico de errores, podrías loguear o mostrar un mensaje al usuario
            session()->flash('error', 'Hubo un error al registrar el paciente: ' . $e->getMessage());
        }
    }

   public function render()
{
    return view('livewire.creacion-pacientes');
        
       

}
}

