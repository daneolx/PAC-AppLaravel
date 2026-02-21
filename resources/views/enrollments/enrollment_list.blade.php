@extends('layouts.app')

@section('title', 'Matrículas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Matrículas</h1>
    <a href="{{ route('enrollment_create') }}" class="btn btn-primary">
        <i class="bi bi-journal-plus"></i> Nueva Matrícula
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('enrollment_list') }}" method="GET" class="row g-3">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" placeholder="Buscar por código o alumno..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Código Matrícula</th>
                        <th>Alumno</th>
                        <th>Producto / Programa</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrollments as $enrollment)
                    <tr>
                        <td><code class="text-primary">{{ $enrollment->enrollment_code }}</code></td>
                        <td>
                            <div class="fw-bold">{{ $enrollment->alumno->full_name }}</div>
                            <small class="text-muted">{{ $enrollment->alumno->numero_documento }}</small>
                        </td>
                        <td>{{ $enrollment->product->sku }} - {{ $enrollment->product->descripcion }}</td>
                        <td>{{ $enrollment->created_at->format('d/m/Y') }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'pre_registered' => 'info',
                                    'registered'     => 'success',
                                    'canceled'       => 'danger',
                                    'completed'      => 'primary',
                                ];
                                $color = $statusColors[$enrollment->enrollment_status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }}">
                                {{ ucfirst(str_replace('_', ' ', $enrollment->enrollment_status)) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('enrollment_detail', $enrollment) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i> Ver Detalle
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No hay matrículas registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $enrollments->links() }}
    </div>
</div>
@endsection
