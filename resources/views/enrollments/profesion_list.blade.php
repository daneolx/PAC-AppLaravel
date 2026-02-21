@extends('layouts.app')

@section('title', 'Lista de Profesiones')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Profesiones</h1>
    <a href="{{ route('profesion_create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle text-white"></i> Nueva Profesión
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="50%">Nombre</th>
                        <th>Descripción</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td class="fw-bold">{{ $item->nombre }}</td>
                        <td>{{ $item->descripcion }}</td>
                        <td class="text-end">
                            <a href="{{ route('profesion_update', $item) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-muted">No hay profesiones registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $items->links() }}
</div>
@endsection
