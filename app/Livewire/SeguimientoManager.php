<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Paciente;
use App\Models\Seguimiento;
use Livewire\Attributes\On;
use Barryvdh\DomPDF\Facade\Pdf; // <<< Añade esta línea
use Illuminate\Http\Response;
use Carbon\Carbon; // Asegúrate de que Carbon esté importado si no lo estaba

class SeguimientoManager extends Component
{
    public Paciente $paciente; // La instancia del paciente se inyectará aquí

    // Propiedades para el formulario de nuevo seguimiento
    public $fecha;
    public $examenes_auxiliares;
    public $diagnostico;
    public $tratamiento_adecuado;
    public $indicaciones;
    public $proxima_cita;
    public $evolucion_texto;

    // Propiedad para controlar si se muestra el formulario de añadir
    public $mostrarFormulario = false;

    // Reglas de validación para el formulario de nuevo seguimiento
    protected $rules = [
        'fecha' => 'required|date',
        'examenes_auxiliares' => 'nullable|string',
        'diagnostico' => 'nullable|string',
        'tratamiento_adecuado' => 'nullable|string',
        'indicaciones' => 'nullable|string',
        'proxima_cita' => 'nullable|date',
        'evolucion_texto' => 'nullable|string',
    ];


    // Método mount() para inicializar el componente con el paciente
    public function mount(Paciente $paciente)
    {
        $this->paciente = $paciente;
    }

    // Método para guardar un nuevo seguimiento
    public function saveSeguimiento()
    {
        $this->validate(); // Valida los datos del formulario

        $this->paciente->seguimientos()->create([
            'fecha' => $this->fecha,
            'examenes_auxiliares' => $this->examenes_auxiliares,
            'diagnostico' => $this->diagnostico,
            'tratamiento_adecuado' => $this->tratamiento_adecuado,
            'indicaciones' => $this->indicaciones,
            'proxima_cita' => $this->proxima_cita,
            'evolucion_texto' => $this->evolucion_texto,
        ]);

        // Limpiar el formulario después de guardar
        $this->reset([
            'fecha', 'examenes_auxiliares', 'diagnostico',
            'tratamiento_adecuado', 'indicaciones', 'proxima_cita', 'evolucion_texto'
        ]);

        // Ocultar el formulario
        $this->mostrarFormulario = false;

        // Emitir un evento para que la lista se actualice si es necesario
        // Aunque render() se llamará automáticamente al cambiar las propiedades
         session()->flash('seguimientoGuardado', 'Seguimiento guardado exitosamente.');
    }

    // Método para mostrar/ocultar el formulario de añadir
    public function toggleForm()
    {
        $this->mostrarFormulario = !$this->mostrarFormulario;
         // Opcional: resetear campos si se abre el formulario
         if($this->mostrarFormulario) {
              $this->reset([
                  'fecha', 'examenes_auxiliares', 'diagnostico',
                  'tratamiento_adecuado', 'indicaciones', 'proxima_cita', 'evolucion_texto'
              ]);
         }
    }

    

    // Opcional: Método para eliminar un seguimiento
    public function deleteSeguimiento(Seguimiento $seguimiento)
    {
        $seguimiento->delete();
        session()->flash('seguimientoEliminado', 'Seguimiento eliminado.');
    }
    public function exportToPdf()
    {
       // Paso 2.1: Asegurarse de que tenemos una instancia de paciente
        if (!isset($this->paciente) || !$this->paciente->exists) {
            session()->flash('error', 'No se pudo exportar el PDF. Paciente no encontrado.');
            return;
        }

        // Paso 2.2: Cargar la vista PDF y pasar la instancia del paciente
        // El método loadView de Dompdf carga la plantilla Blade
        $pdf = Pdf::loadView('pdfs.patient_history', ['paciente' => $this->paciente]); // <<< Asegúrate de que el nombre de la vista sea 'pdfs.patient_history'

        // Paso 2.3: (Opcional) Configurar tamaño y orientación del papel
        // $pdf->setPaper('a4', 'portrait'); // A4 vertical es estándar

        // Paso 2.4: Generar el PDF y forzar la descarga
        // streamDownload fuerza al navegador a descargar el archivo
        $fileName = 'historia_clinica_' . str_replace(' ', '_', $this->paciente->nombre_apellidos) . '_' . $this->paciente->id . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream(); // stream() genera el contenido del PDF
        }, $fileName);

        // Si quisieras ver el PDF en el navegador en lugar de descargarlo, podrías usar:
        // return $pdf->stream($fileName);
    }
    public function exportPrescriptionToPdf()
    {
         // Paso 2.1: Asegurarse de que tenemos una instancia de paciente
         // El paciente ya está disponible como $this->paciente gracias al mount()
         if (!isset($this->paciente) || !$this->paciente->exists) {
             session()->flash('error', 'No se pudo generar la receta. Paciente no encontrado.');
             return;
         }

         // Paso 2.2: Obtener los datos relevantes para la receta
         // Queremos usar el diagnóstico, tratamiento e indicaciones más recientes.
         // Buscamos el último seguimiento por fecha.
         $latestSeguimiento = $this->paciente->seguimientos()->latest('fecha')->first();

         // Si hay un último seguimiento, usamos sus datos.
         // Si no hay seguimientos, usamos los datos iniciales guardados en el modelo Paciente (del Paso 3 inicial).
         $diagnostico = $latestSeguimiento->diagnostico ?? $this->paciente->examen_diagnostico;
         $tratamiento = $latestSeguimiento->tratamiento_adecuado ?? $this->paciente->examen_tratamiento_adecuado;
         $indicaciones = $latestSeguimiento->indicaciones ?? $this->paciente->examen_indicaciones;

         // Paso 2.3: Cargar la vista PDF con los datos
         // Pdf::loadView() carga la plantilla Blade y la prepara para ser renderizada
         $pdf = Pdf::loadView('pdfs.medical_prescription', [
             'paciente' => $this->paciente, // Pasamos la instancia completa del paciente
             'diagnostico' => $diagnostico, // Pasamos el diagnóstico (del último seg o inicial)
             'tratamiento' => $tratamiento, // Pasamos el tratamiento (del último seg o inicial)
             'indicaciones' => $indicaciones, // Pasamos las indicaciones (del último seg o inicial)
         ]);

         // Paso 2.4: (Opcional) Configurar el tamaño y orientación del papel
         // El tamaño A5 es común para recetas.
         $pdf->setPaper('a5', 'portrait'); // 'portrait' para vertical, 'landscape' para horizontal

         // Paso 2.5: Generar el PDF y forzar la descarga
         // streamDownload() permite descargar el archivo sin cargarlo todo en memoria
         // La función anónima echo $pdf->stream() es la que realmente genera el contenido del PDF
         $fileName = 'receta_medica_' . str_replace(' ', '_', $this->paciente->nombre_apellidos) . '_' . $this->paciente->id . '.pdf';

         return response()->streamDownload(function () use ($pdf) {
             echo $pdf->stream();
         }, $fileName);

         // Si quisieras ver el PDF en el navegador en lugar de descargarlo, usarías:
         // return $pdf->stream($fileName);
    }
    // Método render() para obtener los seguimientos del paciente
    public function render()
    {
        // Obtenemos los seguimientos del paciente y los ordenamos por fecha
        $seguimientos = $this->paciente->seguimientos()->latest('fecha')->get();

        return view('livewire.seguimiento-manager', [
            'seguimientos' => $seguimientos,
        ]);
    }
    
}