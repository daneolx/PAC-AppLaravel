@extends('layouts.app')

@section('title', ($item->exists ? 'Editar' : 'Nueva') . ' Profesión')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('profesion_list') }}">Profesiones</a></li>
            <li class="breadcrumb-item active">{{ $item->exists ? 'Editar' : 'Nueva' }}</li>
        </ol>
    </nav>
    <h1>{{ $item->exists ? 'Editar Profesión' : 'Registrar Profesión' }}</h1>
</div>

<div class="card shadow-sm col-md-6">
    <div class="card-body">
        <form action="{{ $item->exists ? route('profesion_update.save', $item) : route('profesion_store') }}" method="POST">
            @csrf
            @if($item->exists)
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre de Profesión</label>
                <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $item->nombre) }}" required>
                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción (Opcional)</label>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="3">{{ old('descripcion', $item->descripcion) }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('profesion_list') }}" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
