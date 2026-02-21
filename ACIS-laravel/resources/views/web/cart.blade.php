@extends('layouts.web')

@section('title', 'Carrito de Compras')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold mb-4">Tu Carrito</h1>
    
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Producto</th>
                                    <th class="py-3 text-center">Cantidad</th>
                                    <th class="py-3 text-end px-4">Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Template de item (vacío por ahora) -->
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <div class="mb-3 text-muted"><i class="bi bi-cart-x fs-1"></i></div>
                                        <h5>Tu carrito está vacío</h5>
                                        <p class="text-muted">¡Agrega algunos cursos para comenzar!</p>
                                        <a href="{{ route('catalogue') }}" class="btn btn-primary mt-2">Ver Catálogo</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Resumen del Pedido</h5>
                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span>Subtotal</span>
                        <span>S/ 0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 fs-5 fw-bold">
                        <span>Total</span>
                        <span class="text-primary">S/ 0.00</span>
                    </div>
                    <button class="btn btn-primary w-100 py-3 fw-bold disabled">Proceder al Pago</button>
                    <div class="mt-3 text-center">
                        <img src="https://vignette.wikia.nocookie.net/mickey-and-friends-pedia/images/f/f3/Culqi.png" alt="Culqi" style="height: 30px" class="opacity-50">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
