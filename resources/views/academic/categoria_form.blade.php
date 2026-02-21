@extends('layouts.app')

@section('title', ($item->id ? 'Editar' : 'Nueva') . ' Categoría')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('categoria_list') }}">Categorías</a></li>
            <li class="breadcrumb-item active">{{ $item->id ? 'Editar' : 'Nueva' }}</li>
        </ol>
    </nav>
    <h1>{{ $item->id ? 'Editar' : 'Nueva' }} Categoría</h1>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ $item->id ? route('categoria_update', $item) : route('categoria_store') }}" method="POST">
                    @csrf
                    @if($item->id) @method('PUT') @endif

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $item->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Estado</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active" {{ old('status', $item->status) === 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="inactive" {{ old('status', $item->status) === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('categoria_list') }}" class="btn btn-outline-secondary">Cancelar</a>
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
