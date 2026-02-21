@extends('layouts.app')

@section('title', 'Programas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Programas</h1>
    <a href="{{ route('program_create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nuevo Programa
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Modalidad</th>
                        <th>Duración</th>
                        <th>Costo Base</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td><code>{{ $item->code }}</code></td>
                        <td>{{ $item->name }}</td>
                        <td>{{ \App\Models\Program::MODALITIES[$item->modality] ?? $item->modality }}</td>
                        <td>{{ $item->duration }}</td>
                        <td>{{ number_format($item->base_cost, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $item->status === 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('program_show', $item) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('program_edit', $item) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No hay programas registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $items->links() }}
    </div>
</div>
@endsection
