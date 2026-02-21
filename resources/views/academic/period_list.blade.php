@extends('layouts.app')

@section('title', 'Periodos Académicos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Periodos Académicos</h1>
    <a href="{{ route('period_create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nuevo Periodo
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Estado Periodo</th>
                        <th>Estado Registro</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->start_date->format('d/m/Y') }}</td>
                        <td>{{ $item->end_date->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $item->period_status === 'open' ? 'success' : 'secondary' }}">
                                {{ $item->period_status === 'open' ? 'Abierto' : 'Cerrado' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $item->status === 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('period_edit', $item) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No hay periodos registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $items->links() }}
    </div>
</div>
@endsection
