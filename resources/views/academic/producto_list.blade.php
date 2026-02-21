@extends('layouts.app')

@section('title', 'Productos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Productos</h1>
    <a href="{{ route('producto_create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nuevo Producto
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('producto_list') }}" method="GET" class="row g-3">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" placeholder="Buscar por SKU o descripción..." value="{{ request('search') }}">
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
                        <th>SKU</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Tema / Categoría</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td><code class="text-primary">{{ $item->sku }}</code></td>
                        <td><span class="badge bg-info text-dark">{{ ucfirst($item->tipo_producto) }}</span></td>
                        <td>{{ $item->descripcion }}</td>
                        <td>
                            <small class="d-block text-muted">{{ $item->tema?->name ?? 'Sin tema' }}</small>
                            <small class="d-block text-muted">{{ $item->categoria?->name ?? 'Sin categoría' }}</small>
                        </td>
                        <td>{{ number_format($item->precio, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $item->status === 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('producto_edit', $item) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No hay productos registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $items->links() }}
    </div>
</div>
@endsection
