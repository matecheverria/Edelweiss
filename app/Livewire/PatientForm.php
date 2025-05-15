<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Paciente;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;

class PatientForm extends Component
{
    use WithFileUploads;

    // Propiedad para almacenar la instancia del paciente (null para creación)
    public ?Paciente $paciente = null;

    // Propiedad para controlar si estamos en modo solo lectura (Ver)
    public $viewMode = false;

    // Propiedades para los campos del formulario (deben coincidir con los wire:model en la vista)
    #[Validate('required|string|max:255')]
    public $nombre_apellidos;

    #[Validate('nullable|integer|min:0')]
    public $edad;

    #[Validate('nullable|numeric|min:0')]
    public $peso;

    #[Validate('nullable|numeric|min:0')]
    public $talla;

    #[Validate('nullable|string|max:255')]
    public $celular;

    // Propiedades para checkboxes (Paso 1)
    #[Validate('boolean')]
    public $enfermedad_diabetes = false;
    #[Validate('boolean')]
    public $enfermedad_hipertension = false;
    #[Validate('boolean')]
    public $enfermedad_marcapaso = false;
    #[Validate('boolean')]
    public $enfermedad_corazon = false;
    #[Validate('boolean')]
    public $usa_anticoagulantes = false;
    #[Validate('boolean')]
    public $artritis_osteoporosis = false;
    #[Validate('boolean')]
    public $usa_protesis = false;

    #[Validate('nullable|string')]
    public $detalle_protesis;

    #[Validate('nullable|string')]
    public $otras_enfermedades;

    #[Validate('nullable|string')]
    public $operaciones;

    #[Validate('nullable|string')]
    public $alergico_a;

    // Propiedades para campos JSON (Paso 2) - Inicializados como arrays vacíos
    #[Validate('nullable|array')]
    public $micosis_interdigital = ['derecho' => [], 'izquierdo' => []];
    #[Validate('nullable|string')]
    public $hiperqueratosis_derecho;
    #[Validate('nullable|string')]
    public $hiperqueratosis_izquierdo;
    #[Validate('nullable|array')]
    public $callos = []; // Inicializado como array vacío
    #[Validate('nullable|array')]
    public $otras_alteraciones_pie = []; // Inicializado como array vacío
    #[Validate('nullable|array')]
    public $alteraciones_dedos_especificas = []; // Inicializado como array vacío
    #[Validate('nullable|array')]
    public $alteraciones_estaticas = []; // Inicializado como array vacío

    // Propiedades para campos de Evaluación de la Piel (Paso 2)
    #[Validate('boolean')]
    public $eval_piel_varices = false;
    #[Validate('boolean')]
    public $eval_piel_trombosis = false;
    #[Validate('boolean')]
    public $eval_piel_edema = false;
    #[Validate('boolean')]
    public $eval_piel_resequedad = false;
    #[Validate('nullable|string')]
    public $eval_piel_otros;


    // Propiedades para Paso 3 inicial (Exámenes y Evolución)
    #[Validate('nullable|string')]
    public $examenes_auxiliares;

    #[Validate('nullable|date')]
    public $examen_fecha;
    #[Validate('nullable|string')]
    public $examen_diagnostico;
    #[Validate('nullable|string')]
    public $examen_tratamiento_adecuado;
    #[Validate('nullable|string')]
    public $examen_indicaciones;
    #[Validate('nullable|date')]
    public $examen_proxima_cita;

    #[Validate('nullable|date')]
    public $evolucion_fecha;
    #[Validate('nullable|string')]
    public $evolucion_texto;

    #[Validate('nullable|string')]
    public $epicrisis;

    // Propiedad para manejar la subida de fotos
    public $photos = [];

    // Propiedad para almacenar las rutas de las fotos existentes (en edición)
    public $existingPhotos = [];


    // Propiedad para controlar el paso actual del formulario
    public $currentStep = 1;

    // Propiedad para el título de la página (usado en la vista)
    public $pageTitle = 'Formulario de Paciente';

    // >>>>>> Reglas de validación por paso <<<<<<
    protected array $step1Rules = [
        'nombre_apellidos' => 'required|string|max:255',
        'edad' => 'nullable|integer|min:0',
        'peso' => 'nullable|numeric|min:0',
        'talla' => 'nullable|numeric|min:0',
        'celular' => 'nullable|string|max:255',
        'enfermedad_diabetes' => 'boolean',
        'enfermedad_hipertension' => 'boolean',
        'enfermedad_marcapaso' => 'boolean',
        'enfermedad_corazon' => 'boolean',
        'usa_anticoagulantes' => 'boolean',
        'artritis_osteoporosis' => 'boolean',
        'usa_protesis' => 'boolean',
        'detalle_protesis' => 'nullable|string',
        'otras_enfermedades' => 'nullable|string',
        'operaciones' => 'nullable|string',
        'alergico_a' => 'nullable|string',
    ];

    protected array $step2Rules = [
        'eval_piel_varices' => 'boolean',
        'eval_piel_trombosis' => 'boolean',
        'eval_piel_edema' => 'boolean',
        'eval_piel_resequedad' => 'boolean',
        'eval_piel_otros' => 'nullable|string',
        'micosis_interdigital' => 'nullable|array',
        'hiperqueratosis_derecho' => 'nullable|string',
        'hiperqueratosis_izquierdo' => 'nullable|string',
        'callos' => 'nullable|array',
        'otras_alteraciones_pie' => 'nullable|array',
        'alteraciones_dedos_especificas' => 'nullable|array',
        'alteraciones_estaticas' => 'nullable|array',
    ];

    protected array $step3Rules = [
        'examenes_auxiliares' => 'nullable|string',
        'examen_fecha' => 'nullable|date',
        'examen_diagnostico' => 'nullable|string',
        'examen_tratamiento_adecuado' => 'nullable|string',
        'examen_indicaciones' => 'nullable|string',
        'examen_proxima_cita' => 'nullable|date',
        'evolucion_fecha' => 'nullable|date',
        'evolucion_texto' => 'nullable|string',
        'epicrisis' => 'nullable|string',
        'photos' => 'nullable|array',
        'photos.*' => 'image|mimes:jpg,jpeg,png,gif|max:1024',
    ];
    // >>>>>> Fin Reglas de validación por paso <<<<<<

    // Propiedad protegida para controlar el layout
    // >>> IMPORTANTE: Inicializar a null aquí para componentes no de página <<<
    protected ?string $layout = null;


    // Método mount se ejecuta al inicializar el componente
    // Recibe la instancia de Paciente si se pasa (en modo edición o ver)
    public function mount(?Paciente $paciente = null)
    {
        // Verificar si la ruta actual es 'pacientes.ver' para activar el modo solo lectura
        if (request()->routeIs('pacientes.ver')) {
            $this->viewMode = true;
            $this->pageTitle = 'Ver Paciente'; // Cambia el título para el modo ver
            // $this->layout = null; // Ya inicializado a null arriba
            Log::info('Modo Ver activado para paciente: ' . ($paciente ? $paciente->id : 'N/A')); // Log de depuración
        } elseif ($paciente) {
             // Si se pasa una instancia de paciente y NO es la ruta 'ver', estamos en modo edición
             $this->pageTitle = 'Editar Paciente'; // Cambia el título para el modo editar
             $this->viewMode = false; // Asegurarse de que no esté en modo ver en edición
             // $this->layout = null; // Ya inicializado a null arriba
             Log::info('Modo Edición activado para paciente: ' . $paciente->id); // Log de depuración
        } else {
             // Si no se proporciona paciente, estamos en modo creación
             $this->pageTitle = 'Crear Nuevo Paciente'; // Título para el modo creación
             $this->viewMode = false; // Asegurarse de que no esté en modo ver en creación
             // $this->layout = null; // Ya inicializado a null arriba
             Log::info('Modo Creación activado.'); // Log de depuración
        }


        // Si se pasa una instancia de paciente (para edición o ver)
        if ($paciente) {
            $this->paciente = $paciente;

            // >>>>>> CARGAR DATOS DEL PACIENTE EN LAS PROPIEDADES DEL COMPONENTE <<<<<<
            // Asegúrate de que los nombres de las propiedades y las columnas coincidan EXACTAMENTE

            // Datos del Paso 1
            $this->nombre_apellidos = $this->paciente->nombre_apellidos;
            $this->edad = $this->paciente->edad;
            $this->peso = $this->paciente->peso;
            $this->talla = $this->paciente->talla;
            $this->celular = $this->paciente->celular;
            $this->enfermedad_diabetes = (bool) $this->paciente->enfermedad_diabetes;
            $this->enfermedad_hipertension = (bool) $this->paciente->enfermedad_hipertension;
            $this->enfermedad_marcapaso = (bool) $this->paciente->enfermedad_marcapaso;
            $this->enfermedad_corazon = (bool) $this->paciente->enfermedad_corazon;
            $this->usa_anticoagulantes = (bool) $this->paciente->usa_anticoagulantes;
            $this->artritis_osteoporosis = (bool) $this->paciente->artritis_osteoporosis;
            $this->usa_protesis = (bool) $this->paciente->usa_protesis;
            $this->detalle_protesis = $this->paciente->detalle_protesis;
            $this->otras_enfermedades = $this->paciente->otras_enfermedades;
            $this->operaciones = $this->paciente->operaciones;
            $this->alergico_a = $this->paciente->alergico_a;

            // Cargar datos del Paso 2 (asegúrate de decodificar JSON si los guardaste así)
            $this->eval_piel_varices = (bool) $this->paciente->eval_piel_varices;
            $this->eval_piel_trombosis = (bool) $this->paciente->eval_piel_trombosis;
            $this->eval_piel_edema = (bool) $this->paciente->eval_piel_edema;
            $this->eval_piel_resequedad = (bool) $this->paciente->eval_piel_resequedad;
            $this->eval_piel_otros = $this->paciente->eval_piel_otros;
            // Decodificar JSON para propiedades que son arrays
            // Usamos ?? [] o ?? $this->propiedad_inicial para asegurar que sea un array si el valor es null en BD
            $this->micosis_interdigital = json_decode($this->paciente->micosis_interdigital ?? '{}', true) ?? ['derecho' => [], 'izquierdo' => []];
            $this->hiperqueratosis_derecho = $this->paciente->hiperqueratosis_derecho;
            $this->hiperqueratosis_izquierdo = $this->paciente->hiperqueratosis_izquierdo;
            $this->callos = json_decode($this->paciente->callos ?? '{}', true) ?? [];
             $this->otras_alteraciones_pie = json_decode($this->paciente->otras_alteraciones_pie ?? '{}', true) ?? [];
             $this->alteraciones_dedos_especificas = json_decode($this->paciente->alteraciones_dedos_especificas ?? '{}', true) ?? [];
             $this->alteraciones_estaticas = json_decode($this->paciente->alteraciones_estaticas ?? '{}', true) ?? [];


            // Cargar datos del Paso 3
            $this->examenes_auxiliares = $this->paciente->examenes_auxiliares_texto;
            $this->examen_fecha = $this->paciente->examen_fecha; // Puede necesitar formateo si no es tipo date en BD
            $this->examen_diagnostico = $this->paciente->examen_diagnostico;
            $this->examen_tratamiento_adecuado = $this->paciente->examen_tratamiento_adecuado;
            $this->examen_indicaciones = $this->paciente->examen_indicaciones;
            $this->examen_proxima_cita = $this->paciente->examen_proxima_cita; // Puede necesitar formateo
            $this->evolucion_fecha = $this->paciente->evolucion_fecha; // Puede necesitar formateo
            $this->evolucion_texto = $this->paciente->evolucion_texto;
            $this->epicrisis = $this->paciente->epicrisis;

            // Cargar rutas de fotos existentes
            $this->existingPhotos = json_decode($this->paciente->photos ?? '[]', true) ?? [];

            // Reiniciar el paso a 1 al cargar para empezar la edición/ver desde el inicio
            $this->currentStep = 1;


        } else {
             // Si no se proporciona paciente (estamos en modo creación), inicializamos las propiedades a sus valores por defecto
             // Usamos reset() y luego inicializamos específicamente los arrays complejos si es necesario
             $this->reset(); // Resetea todas las propiedades públicas a sus valores iniciales (false, null, [], etc.)
             $this->currentStep = 1;
             // Reinicializar específicamente los arrays complejos si reset() no lo hace con defaults complejos
             $this->micosis_interdigital = ['derecho' => [], 'izquierdo' => []];
             $this->callos = [];
             $this->otras_alteraciones_pie = [];
             $this->alteraciones_dedos_especificas = [];
             $this->alteraciones_estaticas = [];
             $this->eval_piel_varices = false;
             $this->eval_piel_trombosis = false;
             $this->eval_piel_edema = false;
             $this->eval_piel_resequedad = false;
             $this->examen_fecha = null; $this->examen_diagnostico = null; $this->examen_tratamiento_adecuado = null; $this->examen_indicaciones = null; $this->examen_proxima_cita = null;
             $this->evolucion_fecha = null; $this->evolucion_texto = null; $this->epicrisis = null;
             $this->detalle_protesis = null;
             $this->otras_enfermedades = null;
             $this->operaciones = null;
             $this->alergico_a = null;
             $this->eval_piel_otros = null;
             $this->photos = [];
             $this->existingPhotos = [];
             $this->viewMode = false; // Asegurar que no esté en modo ver en creación
        }
    }

    // Método para validar el paso actual
    protected function validateCurrentStep()
    {
        // No validar en modo ver
        if ($this->viewMode) {
            Log::info('Validación omitida en modo ver.'); // Log de depuración
            return;
        }
        try {
            if ($this->currentStep == 1) {
                $this->validate($this->step1Rules);
                 Log::info('Paso 1 validado correctamente.'); // Log de depuración
            } elseif ($this->currentStep == 2) {
                $this->validate($this->step2Rules);
                 Log::info('Paso 2 validado correctamente.'); // Log de depuración
            } elseif ($this->currentStep == 3) {
                // La validación del paso 3 se hace en submitForm()
                Log::info('Validación del Paso 3 se realiza en submitForm.'); // Log de depuración
            }
        } catch (ValidationException $e) {
             Log::error('Error de validación en el paso ' . $this->currentStep . ': ' . $e->getMessage()); // Log de error
             throw $e; // Re-lanzar la excepción para que Livewire la maneje
        }
    }


    // Método para avanzar al siguiente paso
    public function nextStep()
    {
        // Solo permitir avanzar si NO estamos en modo ver
        if ($this->viewMode) {
            Log::info('Intento de avanzar paso en modo ver - bloqueado.'); // Log de depuración
            return;
        }

        Log::info('Intentando avanzar al siguiente paso desde el paso: ' . $this->currentStep); // Log de depuración

        // Validar solo los campos del paso actual
        try {
             $this->validateCurrentStep();

             // Si la validación pasa, avanzamos al siguiente paso
             if ($this->currentStep < 3) { // Asegurarse de no avanzar más allá del último paso (Paso 3)
                 $this->currentStep++;
                  Log::info('Avanzado al paso: ' . $this->currentStep); // Log de depuración
             } else {
                  Log::info('Ya en el último paso (Paso 3).'); // Log de depuración
             }
        } catch (ValidationException $e) {
            // La excepción ya fue loggeada en validateCurrentStep
            // Re-lanzar para que Livewire la maneje
            throw $e;
        } catch (\Exception $e) {
             // Capturar cualquier otra excepción que ocurra después de la validación
             Log::error('Error inesperado en nextStep: ' . $e->getMessage());
             // Opcional: Mostrar un mensaje de error al usuario
             // Session::flash('error', 'Ocurrió un error al avanzar al siguiente paso.');
        }
    }

    // Método para retroceder al paso anterior
    public function previousStep()
    {
         // Solo permitir retroceder si NO estamos en modo ver
        if ($this->viewMode) {
             Log::info('Intento de retroceder paso en modo ver - bloqueado.'); // Log de depuración
            return;
        }

        Log::info('Intentando retroceder al paso anterior desde el paso: ' . $this->currentStep); // Log de depuración

        if ($this->currentStep > 1) {
            $this->currentStep--;
             Log::info('Retrocedido al paso: ' . $this->currentStep); // Log de depuración
        } else {
             Log::info('Ya en el primer paso (Paso 1).'); // Log de depuración
        }
    }

    // Método para guardar o actualizar el paciente (se llama en el último paso)
    public function submitForm()
    {
         // No permitir guardar si NO estamos en modo ver
        if ($this->viewMode) {
             Log::info('Intento de guardar en modo ver - bloqueado.'); // Log de depuración
            return;
        }

        Log::info('Intentando guardar/actualizar el formulario.'); // Log de depuración

         // Asegúrate de que el Paso actual sea 3 antes de intentar guardar
         if ($this->currentStep != 3) {
             Log::info('Intento de guardar antes del Paso 3 - bloqueado.'); // Log de depuración
             return; // No hacer nada si no estamos en el Paso 3
         }


        // Validar todos los campos antes de guardar
        try {
            $this->validate(array_merge($this->step1Rules, $this->step2Rules, $this->step3Rules));
             Log::info('Validación final del formulario correcta.'); // Log de depuración
        } catch (ValidationException $e) {
             Log::error('Error de validación final: ' . $e->getMessage()); // Log de error
             throw $e; // Re-lanzar la excepción
        }


        // Lógica para guardar/actualizar el paciente
        $pacienteData = [
            'nombre_apellidos' => $this->nombre_apellidos,
            'edad' => $this->edad,
            'peso' => $this->peso,
            'talla' => $this->talla,
            'celular' => $this->celular,
            'enfermedad_diabetes' => $this->enfermedad_diabetes,
            'enfermedad_hipertension' => $this->enfermedad_hipertension,
            'enfermedad_marcapaso' => $this->enfermedad_marcapaso,
            'enfermedad_corazon' => $this->enfermedad_corazon,
            'usa_anticoagulantes' => $this->usa_anticoagulantes,
            'artritis_osteoporosis' => $this->artritis_osteoporosis,
            'usa_protesis' => $this->usa_protesis,
            'detalle_protesis' => $this->detalle_protesis,
            'otras_enfermedades' => $this->otras_enfermedades,
            'operaciones' => $this->operaciones,
            'alergico_a' => $this->alergico_a,
            'eval_piel_varices' => $this->eval_piel_varices,
            'eval_piel_trombosis' => $this->eval_piel_trombosis,
            'eval_piel_edema' => $this->eval_piel_edema,
            'eval_piel_resequedad' => $this->eval_piel_resequedad,
            'eval_piel_otros' => $this->eval_piel_otros,
            'micosis_interdigital' => json_encode($this->micosis_interdigital),
            'hiperqueratosis_derecho' => $this->hiperqueratosis_derecho,
            'hiperqueratosis_izquierdo' => $this->hiperqueratosis_izquierdo,
            'callos' => json_encode($this->callos),
            'otras_alteraciones_pie' => json_encode($this->otras_alteraciones_pie),
            'alteraciones_dedos_especificas' => json_encode($this->alteraciones_dedos_especificas),
            'alteraciones_estaticas' => json_encode($this->alteraciones_estaticas),
            'examenes_auxiliares_texto' => $this->examenes_auxiliares, // Corregido el nombre del campo
            'examen_fecha' => $this->examen_fecha,
            'examen_diagnostico' => $this->examen_diagnostico,
            'examen_tratamiento_adecuado' => $this->examen_tratamiento_adecuado,
            'examen_indicaciones' => $this->examen_indicaciones,
            'examen_proxima_cita' => $this->examen_proxima_cita,
            'evolucion_fecha' => $this->evolucion_fecha,
            'evolucion_texto' => $this->evolucion_texto,
            'epicrisis' => $this->epicrisis,
        ];

        // Procesar y guardar nuevas fotos si existen
        $newPhotoPaths = $this->storePhotos();

        // Combinar fotos existentes con las nuevas
        $allPhotoPaths = array_merge($this->existingPhotos, $newPhotoPaths);
        $pacienteData['photos'] = json_encode($allPhotoPaths);


        if ($this->paciente) {
            // Actualizar paciente existente
            $this->paciente->update($pacienteData);
            Session::flash('pacienteActualizado', 'Paciente actualizado exitosamente.');
             Log::info('Paciente actualizado: ' . $this->paciente->id); // Log de éxito
        } else {
            // Crear nuevo paciente
            $paciente = Paciente::create($pacienteData);
            Session::flash('pacienteCreado', 'Paciente creado exitosamente.');
             Log::info('Nuevo paciente creado: ' . $paciente->id); // Log de éxito
        }

        // Redirigir al listado de pacientes después de guardar
        return redirect()->route('pacientes.index');
    }


    protected function storePhotos(): array
    {
        $paths = [];
        // Asegurarse de que $this->photos sea iterable y no nulo
        if (is_array($this->photos)) {
            foreach ($this->photos as $photo) {
                 // Verificar que $photo es una instancia de UploadedFile
                if ($photo instanceof \Illuminate\Http\UploadedFile) {
                     $paths[] = $photo->store('paciente_photos', 'public');
                }
            }
        } // Si $this->photos no es un array pero no es nulo, no hacemos nada aquí ya que esperamos multiples archivos.
        // Si solo permitieras una foto, la lógica sería diferente.
        return $paths;
    }

    // Método para eliminar una foto existente
    public function deletePhoto($photoPath)
    {
         // Solo permitir eliminar si NO estamos en modo ver
        if ($this->viewMode) {
             Log::info('Intento de eliminar foto en modo ver - bloqueado.'); // Log de depuración
            return;
        }

         // Eliminar el archivo del almacenamiento
         if (\Illuminate\Support\Facades\Storage::disk('public')->exists($photoPath)) {
             \Illuminate\Support\Facades\Storage::disk('public')->delete($photoPath);
             Log::info('Foto eliminada del almacenamiento: ' . $photoPath); // Log de acción
         }

         // Eliminar la ruta del array existingPhotos
         $this->existingPhotos = array_values(array_filter($this->existingPhotos, function ($path) use ($photoPath) {
             return $path !== $photoPath;
         }));
         Log::info('Foto eliminada del array existingPhotos.'); // Log de acción

         // Actualizar el campo 'photos' en la base de datos
         if ($this->paciente) {
             $this->paciente->update(['photos' => json_encode($this->existingPhotos)]);
             Log::info('Campo photos actualizado en la base de datos para paciente: ' . $this->paciente->id); // Log de acción
         }

         Session::flash('message', 'Foto eliminada.'); // Mensaje de confirmación
    }


    public function resetForm()
    {
        $this->reset();
        $this->currentStep = 1;
        unset($this->paciente);
        $this->micosis_interdigital = ['derecho' => [], 'izquierdo' => []];
        $this->callos = [];
        $this->otras_alteraciones_pie = [];
        $this->alteraciones_dedos_especificas = [];
        $this->alteraciones_estaticas = [];
        $this->eval_piel_varices = false;
        $this->eval_piel_trombosis = false;
        $this->eval_piel_edema = false;
        $this->eval_piel_resequedad = false;
        $this->examen_fecha = null; $this->examen_diagnostico = null; $this->examen_tratamiento_adecuado = null; $this->examen_indicaciones = null; $this->examen_proxima_cita = null;
        $this->evolucion_fecha = null; $this->evolucion_texto = null; $this->epicrisis = null;
        $this->detalle_protesis = null;
        $this->otras_enfermedades = null;
        $this->operaciones = null;
        $this->alergico_a = null;
        $this->eval_piel_otros = null;
        $this->photos = [];
        $this->existingPhotos = [];
        $this->viewMode = false; // Asegurar que no esté en modo ver
    }

     public function render()
     {
         // El pageTitle ya se establece en mount()
         return view('livewire.patient-form', [
              'pageTitle' => $this->pageTitle
         ]);
     }
}
