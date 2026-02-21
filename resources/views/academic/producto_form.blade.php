@extends('layouts.app')

@section('title', ($item->id ? 'Editar' : 'Nuevo') . ' Producto')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('producto_list') }}">Productos</a></li>
            <li class="breadcrumb-item active">{{ $item->id ? 'Editar' : 'Nuevo' }}</li>
        </ol>
    </nav>
    <h1>{{ $item->id ? 'Editar' : 'Nuevo' }} Producto</h1>
</div>

<form action="{{ $item->id ? route('producto_update', $item) : route('producto_store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($item->id) @method('PUT') @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Información General</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="tipo_producto" class="form-label">Tipo de Producto</label>
                            <select name="tipo_producto" id="tipo_producto" class="form-select" required>
                                @foreach(\App\Models\Producto::TIPOS as $key => $label)
                                <option value="{{ $key }}" {{ old('tipo_producto', $item->tipo_producto) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="sku" class="form-label">SKU / Código</label>
                            <input type="text" name="sku" id="sku" class="form-control" value="{{ old('sku', $item->sku) }}" required>
                        </div>
                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="form-control" rows="3">{{ old('descripcion', $item->descripcion) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="categoria_id" class="form-label">Categoría</label>
                            <select name="categoria_id" id="categoria_id" class="form-select">
                                <option value="">Seleccione...</option>
                                @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}" {{ old('categoria_id', $item->categoria_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tema_id" class="form-label">Tema</label>
                            <select name="tema_id" id="tema_id" class="form-select">
                                <option value="">Seleccione...</option>
                                @foreach($temas as $tema)
                                <option value="{{ $tema->id }}" {{ old('tema_id', $item->tema_id) == $tema->id ? 'selected' : '' }}>{{ $tema->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Detalles Académicos y Costos</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="horas" class="form-label">Horas</label>
                            <input type="number" name="horas" id="horas" class="form-control" value="{{ old('horas', $item->horas ?? 0) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="creditos" class="form-label">Créditos</label>
                            <input type="number" name="creditos" id="creditos" class="form-control" value="{{ old('creditos', $item->creditos ?? 0) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="precio" class="form-label">Precio Regular</label>
                            <input type="number" step="0.01" name="precio" id="precio" class="form-control" value="{{ old('precio', $item->precio ?? 0) }}" required>
                        </div>
                        
                        <div id="diplomado_fields" class="row g-3 mt-0" style="display: {{ old('tipo_producto', $item->tipo_producto) === 'diplomado' ? 'flex' : 'none' }}">
                            <div class="col-md-4">
                                <label for="meses" class="form-label">Meses / Cuotas</label>
                                <input type="number" name="meses" id="meses" class="form-control" value="{{ old('meses', $item->meses ?? 1) }}">
                            </div>
                            <div class="col-md-4">
                                <label for="matricula" class="form-label">Matrícula</label>
                                <input type="number" step="0.01" name="matricula" id="matricula" class="form-control" value="{{ old('matricula', $item->matricula ?? 0) }}">
                            </div>
                            <div class="col-md-4">
                                <label for="mensualidad" class="form-label">Mensualidad</label>
                                <input type="number" step="0.01" name="mensualidad" id="mensualidad" class="form-control" value="{{ old('mensualidad', $item->mensualidad ?? 0) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ old('fecha_inicio', $item->fecha_inicio?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_fin" class="form-label">Fecha Fin</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ old('fecha_fin', $item->fecha_fin?->format('Y-m-d')) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Estado y Etiquetas</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Estado</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active" {{ old('status', $item->status) === 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="inactive" {{ old('status', $item->status) === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Etiquetas</label>
                        <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                            @foreach($etiquetas as $tag)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="etiquetas[]" value="{{ $tag->id }}" id="tag{{ $tag->id }}" {{ in_array($tag->id, old('etiquetas', $item->etiquetas->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <label class="form-check-label" for="tag{{ $tag->id }}">
                                    {{ $tag->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen</label>
                        <input type="file" name="imagen" id="imagen" class="form-control">
                        @if($item->imagen)
                        <div class="mt-2 text-center">
                            <img src="{{ asset('storage/' . $item->imagen) }}" class="img-thumbnail" style="max-height: 150px">
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Configuración Extra</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="moodle_course_id" class="form-label">Moodle Course ID</label>
                        <input type="number" name="moodle_course_id" id="moodle_course_id" class="form-control" value="{{ old('moodle_course_id', $item->moodle_course_id) }}">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="requiere_membresia" id="requiere_membresia" value="1" {{ old('requiere_membresia', $item->requiere_membresia) ? 'checked' : '' }}>
                        <label class="form-check-label" for="requiere_membresia">Requiere Membresía</label>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save"></i> Guardar Producto
                </button>
                <a href="{{ route('producto_list') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.getElementById('tipo_producto').addEventListener('change', function() {
    const fields = document.getElementById('diplomado_fields');
    fields.style.display = (this.value === 'diplomado') ? 'flex' : 'none';
});
</script>
@endpush
@endsection
