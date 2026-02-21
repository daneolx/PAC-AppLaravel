@extends('layouts.web')

@section('title', 'Inicio')

@section('content')
<div class="hero-section text-center">
    <div class="container">
        <h1 class="display-3 fw-bold mb-4">Potencia tu Futuro Profesional</h1>
        <p class="lead mb-5">Capacitación especializada de alta calidad. Cursos, Diplomados y Especializaciones para el mundo actual.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('catalogue') }}" class="btn btn-light btn-lg px-5">Ver Catálogo</a>
            @guest
            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-5">Regístrate</a>
            @endguest
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="p-4">
                <i class="bi bi-clock-history fs-1 text-primary"></i>
                <h3 class="mt-3">Flexibilidad</h3>
                <p class="text-muted">Aprende a tu ritmo con nuestras modalidades online y grabadas.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4">
                <i class="bi bi-award fs-1 text-primary"></i>
                <h3 class="mt-3">Certificación</h3>
                <p class="text-muted">Obtén certificaciones con validez académica para tu currículum.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4">
                <i class="bi bi-person-check fs-1 text-primary"></i>
                <h3 class="mt-3">Expertos</h3>
                <p class="text-muted">Docentes con amplia experiencia en el sector y trayectoria académica.</p>
            </div>
        </div>
    </div>
</div>
@endsection
