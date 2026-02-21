@extends('layouts.app')

@section('title', 'Detalle de Alumno - ' . $alumno->full_name)

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('alumno_list') }}">Alumnos</a></li>
                <li class="breadcrumb-item active">{{ $alumno->full_name }}</li>
            </ol>
        </nav>
        <h1>{{ $alumno->full_name }}</h1>
    </div>
    <a href="{{ route('alumno_update', $alumno) }}" class="btn btn-outline-primary">
        <i class="bi bi-pencil"></i> Editar Perfil
    </a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Información Personal</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small d-block">Documento</label>
                    <span class="fw-bold">{{ $alumno->tipo_documento }}: {{ $alumno->numero_documento }}</span>
                </div>
                <div class="mb-3">
                    <label class="text-muted small d-block">Correo Electrónico</label>
                    <span class="fw-bold">{{ $alumno->email }}</span>
                </div>
                <div class="mb-3">
                    <label class="text-muted small d-block">Teléfono</label>
                    <span class="fw-bold">{{ $alumno->telefono ?? 'No registrado' }}</span>
                </div>
                <div class="mb-3">
                    <label class="text-muted small d-block">Estado</label>
                    <span class="badge bg-{{ $alumno->estado === 'activo' ? 'success' : 'danger' }}">
                        {{ ucfirst($alumno->estado) }}
                    </span>
                </div>
                <hr>
                <div class="mb-3">
                    <label class="text-muted small d-block">Profesión</label>
                    <span>{{ $alumno->profesion->nombre ?? 'N/A' }}</span>
                </div>
                <div class="mb-3">
                    <label class="text-muted small d-block">Grado Académico</label>
                    <span>{{ $alumno->grado_academico ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Matrículas / Programas</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Programa / Producto</th>
                                <th>Estado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alumno->enrollments as $enrollment)
                            <tr>
                                <td class="fw-bold">{{ $enrollment->enrollment_code }}</td>
                                <td>{{ $enrollment->product->descripcion }}</td>
                                <td>
                                    <span class="badge bg-{{ $enrollment->enrollment_status === 'registered' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($enrollment->enrollment_status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('enrollment_detail', $enrollment) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No tiene matrículas registradas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Direcciones</h5>
                <button class="btn btn-sm btn-outline-primary">Agregar Dirección</button>
            </div>
            <div class="card-body">
                @forelse($alumno->direcciones as $dir)
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded">
                        <div>
                            <div class="fw-bold">{{ $dir->street_address }}</div>
                            <small class="text-muted">{{ $dir->distrito->name }}, {{ $dir->distrito->province }}, {{ $dir->distrito->department }}</small>
                        </div>
                        <span class="badge bg-secondary">{{ ucfirst($dir->address_type) }}</span>
                    </div>
                @empty
                    <p class="text-muted text-center py-3 mb-0">No hay direcciones registradas.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
