@extends('layouts.app')

@section('title', ($item->id ? 'Editar' : 'Nuevo') . ' Periodo')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('period_list') }}">Periodos</a></li>
            <li class="breadcrumb-item active">{{ $item->id ? 'Editar' : 'Nuevo' }}</li>
        </ol>
    </nav>
    <h1>{{ $item->id ? 'Editar' : 'Nuevo' }} Periodo</h1>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ $item->id ? route('period_update', $item) : route('period_store') }}" method="POST">
                    @csrf
                    @if($item->id) @method('PUT') @endif

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Periodo</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $item->name) }}" placeholder="Ej: 2024-I" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Fecha de Inicio</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $item->start_date?->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">Fecha de Fin</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $item->end_date?->format('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="period_status" class="form-label">Estado Periodo</label>
                            <select name="period_status" id="period_status" class="form-select" required>
                                <option value="open" {{ old('period_status', $item->period_status) === 'open' ? 'selected' : '' }}>Abierto</option>
                                <option value="closed" {{ old('period_status', $item->period_status) === 'closed' ? 'selected' : '' }}>Cerrado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Estado Registro</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="active" {{ old('status', $item->status) === 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="inactive" {{ old('status', $item->status) === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('period_list') }}" class="btn btn-outline-secondary">Cancelar</a>
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
