@extends('layouts.app')

@section('title', ($item->id ? 'Editar' : 'Nuevo') . ' Programa')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('program_list') }}">Programas</a></li>
            <li class="breadcrumb-item active">{{ $item->id ? 'Editar' : 'Nuevo' }}</li>
        </ol>
    </nav>
    <h1>{{ $item->id ? 'Editar' : 'Nuevo' }} Programa</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ $item->id ? route('program_update', $item) : route('program_store') }}" method="POST">
                    @csrf
                    @if($item->id) @method('PUT') @endif

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="name" class="form-label">Nombre del Programa</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $item->name) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="code" class="form-label">Código</label>
                            <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $item->code) }}" required>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $item->description) }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="modality" class="form-label">Modalidad</label>
                            <select name="modality" id="modality" class="form-select" required>
                                <option value="">Seleccione...</option>
                                @foreach($modalities as $key => $label)
                                <option value="{{ $key }}" {{ old('modality', $item->modality) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="duration" class="form-label">Duración</label>
                            <input type="text" name="duration" id="duration" class="form-control" value="{{ old('duration', $item->duration) }}" placeholder="Ej: 6 meses" required>
                        </div>
                        <div class="col-md-4">
                            <label for="base_cost" class="form-label">Costo Base</label>
                            <input type="number" step="0.01" name="base_cost" id="base_cost" class="form-control" value="{{ old('base_cost', $item->base_cost) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="max_students" class="form-label">Capacidad Máxima</label>
                            <input type="number" name="max_students" id="max_students" class="form-control" value="{{ old('max_students', $item->max_students ?? 30) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">Estado</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="active" {{ old('status', $item->status) === 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="inactive" {{ old('status', $item->status) === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('program_list') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
