@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h1>Bienvenido, {{ Auth::user()->name }}</h1>
    <p class="text-muted">Resumen general del sistema ACIS.</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 opacity-75">Matrículas Hoy</h6>
                        <h2 class="mb-0 fw-bold">12</h2>
                    </div>
                    <i class="bi bi-person-plus fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 opacity-75">Cobros Hoy</h6>
                        <h2 class="mb-0 fw-bold">S/ 4,250</h2>
                    </div>
                    <i class="bi bi-cash-coin fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 opacity-75">Pendientes</h6>
                        <h2 class="mb-0 fw-bold">8</h2>
                    </div>
                    <i class="bi bi-exclamation-circle fs-1 opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 opacity-75">Cursos Activos</h6>
                        <h2 class="mb-0 fw-bold">24</h2>
                    </div>
                    <i class="bi bi-book fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Actividad Reciente</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1 fw-bold text-success">Pago validado</h6>
                            <small class="text-muted">Hace 5 min</small>
                        </div>
                        <p class="mb-1 small">El pago de Juan Pérez por diplomado de Gestión en Salud fue validado.</p>
                    </div>
                    <div class="list-group-item px-0">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1 fw-bold text-primary">Nueva matrícula</h6>
                            <small class="text-muted">Hace 15 min</small>
                        </div>
                        <p class="mb-1 small">Maria Garcia se ha pre-matriculado en el curso de Auditoría Médica.</p>
                    </div>
                    <div class="list-group-item px-0 text-center py-3">
                        <a href="{{ route('audit_log') }}" class="btn btn-sm btn-link">Ver todo el historial</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Accesos Rápidos</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('enrollment_create') }}" class="btn btn-outline-primary text-start"><i class="bi bi-plus-circle me-2"></i> Nueva Matrícula</a>
                    <a href="{{ route('payment_list') }}" class="btn btn-outline-success text-start"><i class="bi bi-check2-circle me-2"></i> Validar Pagos</a>
                    <a href="{{ route('alumno_create') }}" class="btn btn-outline-info text-start"><i class="bi bi-person-plus me-2"></i> Registrar Alumno</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
