@extends('layouts.app')

@section('title', ($alumno->exists ? 'Editar' : 'Nuevo') . ' Alumno')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('alumno_list') }}">Alumnos</a></li>
            <li class="breadcrumb-item active">{{ $alumno->exists ? 'Editar' : 'Nuevo' }}</li>
        </ol>
    </nav>
    <h1>{{ $alumno->exists ? 'Editar Alumno: ' . $alumno->full_name : 'Registrar Nuevo Alumno' }}</h1>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ $alumno->exists ? route('alumno_update.save', $alumno) : route('alumno_store') }}" method="POST">
            @csrf
            @if($alumno->exists)
                @method('PUT')
            @endif

            <div class="row g-3">
                <div class="col-md-4">
                    <label for="tipo_documento" class="form-label">Tipo Documento</label>
                    <select name="tipo_documento" id="tipo_documento" class="form-select @error('tipo_documento') is-invalid @enderror" required>
                        @foreach(\App\Models\Alumno::TIPOS_DOCUMENTO as $val => $label)
                            <option value="{{ $val }}" {{ old('tipo_documento', $alumno->tipo_documento) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('tipo_documento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="numero_documento" class="form-label">Número Documento</label>
                    <input type="text" name="numero_documento" id="numero_documento" class="form-control @error('numero_documento') is-invalid @enderror" value="{{ old('numero_documento', $alumno->numero_documento) }}" required>
                    @error('numero_documento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select" required>
                        <option value="activo" {{ old('estado', $alumno->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('estado', $alumno->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="nombre" class="form-label">Nombres</label>
                    <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $alumno->nombre) }}" required>
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                    <input type="text" name="apellido_paterno" id="apellido_paterno" class="form-control @error('apellido_paterno') is-invalid @enderror" value="{{ old('apellido_paterno', $alumno->apellido_paterno) }}" required>
                    @error('apellido_paterno') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="apellido_materno" class="form-label">Apellido Materno</label>
                    <input type="text" name="apellido_materno" id="apellido_materno" class="form-control @error('apellido_materno') is-invalid @enderror" value="{{ old('apellido_materno', $alumno->apellido_materno) }}">
                    @error('apellido_materno') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $alumno->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="telefono" class="form-label">Teléfono / Celular</label>
                    <input type="text" name="telefono" id="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', $alumno->telefono) }}">
                    @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label for="fecha_nacimiento" class="form-label">Fecha Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento', $alumno->fecha_nacimiento?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label for="discapacidad" class="form-label">Discapacidad</label>
                    <select name="discapacidad" id="discapacidad" class="form-select">
                        <option value="NO" {{ old('discapacidad', $alumno->discapacidad) == 'NO' ? 'selected' : '' }}>NO</option>
                        <option value="SI" {{ old('discapacidad', $alumno->discapacidad) == 'SI' ? 'selected' : '' }}>SI</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="profesion_id" class="form-label">Profesión</label>
                    <select name="profesion_id" id="profesion_id" class="form-select">
                        <option value="">Seleccione...</option>
                        @foreach($profesiones as $p)
                            <option value="{{ $p->id }}" {{ old('profesion_id', $alumno->profesion_id) == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="grado_academico" class="form-label">Grado Académico</label>
                    <input type="text" name="grado_academico" id="grado_academico" class="form-control" value="{{ old('grado_academico', $alumno->grado_academico) }}">
                </div>
                <div class="col-md-6">
                    <label for="colegiatura" class="form-label">Nro. Colegiatura</label>
                    <input type="text" name="colegiatura" id="colegiatura" class="form-control" value="{{ old('colegiatura', $alumno->colegiatura) }}">
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('alumno_list') }}" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ $alumno->exists ? 'Actualizar' : 'Guardar' }} Alumno
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
