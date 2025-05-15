<x-layouts.app>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow rounded" >    
    <h2 class="text-2xl font-bold mb-6">Registrar nuevo paciente</h2>

    {{-- Livewire maneja la visualización de errores automáticamente con wire:model.
         Puedes mostrar errores específicos si lo deseas usando @error --}}
    @if (session()->has('message'))
        <div class="alert alert-success mb-4" >
            {{ session('message') }}
        </div>
    @endif

     @if (session()->has('error'))
        <div class="alert alert-danger mb-4">
            {{ session('error') }}
        </div>
    @endif


    {{-- wire:submit.prevent llama al método savePatient y previene el submit por defecto del navegador --}}
    <form wire:submit.prevent="savePatient">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="nombre_completo" class="block text-sm font-medium text-gray-700">Nombre completo</label>
                {{-- wire:model bindea este input a la propiedad $nombre_completo del componente PHP --}}
                <input type="text" id="nombre_completo" wire:model="nombre_completo" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-green-500 focus:border-green-500 sm:text-sm" required>
                 @error('nombre_completo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="edad" class="block text-sm font-medium text-gray-700">Edad</label>
                <input type="number" id="edad" wire:model="edad" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-green-500 focus:border-green-500 sm:text-sm">
                @error('edad') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="peso" class="block text-sm font-medium text-gray-700">Peso (kg)</label>
                <input type="number" step="0.1" id="peso" wire:model="peso" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-green-500 focus:border-green-500 sm:text-sm">
                @error('peso') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="talla" class="block text-sm font-medium text-gray-700">Talla (m)</label>
                <input type="number" step="0.01" id="talla" wire:model="talla" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-green-500 focus:border-green-500 sm:text-sm">
                @error('talla') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="md:col-span-2">
                <label for="celular" class="block text-sm font-medium text-gray-700">Celular</label>
                <input type="text" id="celular" wire:model="celular" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-green-500 focus:border-green-500 sm:text-sm">
                @error('celular') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-700">Enfermedades</label>
                 @error('enfermedades') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                <div class="grid grid-cols-2 gap-2">
                    @foreach(['diabetes', 'hipertension', 'marcapaso', 'cardiopatía', 'anticoagulantes', 'artritis', 'osteoporosis', 'prótesis'] as $enfermedad)
                        <label class="inline-flex items-center">
                            {{-- wire:model.live bindea este checkbox al array $enfermedades --}}
                            <input type="checkbox" wire:model.live="enfermedades" value="{{ $enfermedad }}" class="rounded text-green-600 focus:ring-green-500 mr-2">
                            <span class="text-sm text-gray-700">{{ ucfirst($enfermedad) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="md:col-span-2">
                <label for="otras_enfermedades" class="block text-sm font-medium text-gray-700">Otras enfermedades</label>
                <textarea id="otras_enfermedades" wire:model="otras_enfermedades" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-green-500 focus:border-green-500 sm:text-sm" rows="2"></textarea>
                 @error('otras_enfermedades') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label for="operaciones" class="block text-sm font-medium text-gray-700">Operaciones</label>
                <textarea id="operaciones" wire:model="operaciones" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-green-500 focus:border-green-500 sm:text-sm" rows="2"></textarea>
                 @error('operaciones') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label for="alergico_a" class="block text-sm font-medium text-gray-700">Alérgico a</label>
                <input type="text" id="alergico_a" wire:model="alergico_a" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-green-500 focus:border-green-500 sm:text-sm">
                 @error('alergico_a') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                 {{-- Opcional: indicador de carga --}}
                 <span wire:loading.remove>Guardar paciente</span>
                 <span wire:loading>Guardando...</span>
            </button>
        </div>
    </form>
    </div>
    </x-layouts.app>
