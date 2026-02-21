@extends('layouts.app')

@section('title', 'Lista de Alumnos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Alumnos</h1>
    <a href="{{ route('alumno_create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus text-white"></i> Nuevo Alumno
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('alumno_list') }}" method="GET" class="row g-3">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, documento o email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-outline-secondary">Buscar</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre Completo</th>
                        <th>Documento</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alumnos as $alumno)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $alumno->full_name }}</div>
                            <small class="text-muted">{{ $alumno->profesion->nombre ?? 'Sin profesión' }}</small>
                        </td>
                        <td>{{ $alumno->tipo_documento }}: {{ $alumno->numero_documento }}</td>
                        <td>{{ $alumno->email }}</td>
                        <td>{{ $alumno->telefono }}</td>
                        <td>
                            <span class="badge bg-{{ $alumno->estado === 'activo' ? 'success' : 'danger' }}">
                                {{ ucfirst($alumno->estado) }}
                            </span>
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('alumno_detail', $alumno) }}" class="btn btn-sm btn-outline-info" title="Ver detalle">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('alumno_update', $alumno) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No se encontraron alumnos.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $alumnos->links() }}
</div>
@endsection
