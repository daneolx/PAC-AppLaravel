@extends('layouts.web')

@section('title', 'Catálogo de Cursos')

@section('content')
<div class="bg-light py-5">
    <div class="container text-center">
        <h1 class="fw-bold">Nuestros Cursos y Programas</h1>
        <p class="lead text-muted">Encuentra la capacitación que mejor se adapte a tus necesidades.</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        @forelse($productos as $producto)
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 course-card">
                @if($producto->imagen)
                <img src="{{ asset('storage/' . $producto->imagen) }}" class="card-img-top" alt="{{ $producto->sku }}">
                @else
                <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 160px">
                    <i class="bi bi-book fs-1"></i>
                </div>
                @endif
                <div class="card-body">
                    <span class="badge bg-primary mb-2">{{ ucfirst($producto->tipo_producto) }}</span>
                    <h5 class="card-title fw-bold">{{ $producto->descripcion }}</h5>
                    <p class="card-text text-muted small">{{ Str::limit($producto->descripcion, 100) }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="fs-5 fw-bold text-primary">S/ {{ number_format($producto->precio, 2) }}</span>
                        <a href="{{ route('cart') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-cart-plus"></i></a>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 pb-3">
                    <a href="{{ route('register') }}" class="btn btn-primary w-100">Matricularse Ahora</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">No se encontraron productos en este momento.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $productos->links() }}
    </div>
</div>
@endsection
