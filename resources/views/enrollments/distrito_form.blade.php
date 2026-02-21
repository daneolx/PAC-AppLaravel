@extends('layouts.app')

@section('title', ($item->exists ? 'Editar' : 'Nuevo') . ' Distrito')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('distrito_list') }}">Distritos</a></li>
            <li class="breadcrumb-item active">{{ $item->exists ? 'Editar' : 'Nuevo' }}</li>
        </ol>
    </nav>
    <h1>{{ $item->exists ? 'Editar Distrito' : 'Registrar Distrito' }}</h1>
</div>

<div class="card shadow-sm col-md-6">
    <div class="card-body">
        <form action="{{ $item->exists ? route('distrito_update.save', $item) : route('distrito_store') }}" method="POST">
            @csrf
            @if($item->exists)
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Distrito</label>
                <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $item->nombre) }}" required>
                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="provincia" class="form-label">Provincia</label>
                <input type="text" name="provincia" id="provincia" class="form-control @error('provincia') is-invalid @enderror" value="{{ old('provincia', $item->provincia) }}">
                @error('provincia') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="departamento" class="form-label">Departamento</label>
                <input type="text" name="departamento" id="departamento" class="form-control @error('departamento') is-invalid @enderror" value="{{ old('departamento', $item->departamento) }}">
                @error('departamento') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('distrito_list') }}" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
