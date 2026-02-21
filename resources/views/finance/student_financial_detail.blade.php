@extends('layouts.app')

@section('title', 'Estado Financiero - ' . $enrollment->enrollment_code)

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('enrollment_list') }}">Matrículas</a></li>
                <li class="breadcrumb-item active">Estado Financiero</li>
            </ol>
        </nav>
        <h1>Estado Financiero: {{ $enrollment->enrollment_code }}</h1>
        <p class="text-muted">Alumno: <strong>{{ $enrollment->alumno->full_name }}</strong> | Producto: <strong>{{ $enrollment->product->descripcion }}</strong></p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('installment_plan', $enrollment) }}" class="btn btn-outline-warning">
            <i class="bi bi-calendar-range"></i> Gestionar Plan
        </a>
        <a href="{{ route('payment_create', $enrollment) }}" class="btn btn-success text-white">
            <i class="bi bi-cash-stack"></i> Registrar Pago
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Resumen de Saldos -->
    <div class="col-md-12">
        <div class="card shadow-sm border-0 border-start border-primary border-4">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4 border-end">
                        <small class="text-muted d-block text-uppercase">Costo Total</small>
                        <h3 class="fw-bold mb-0">S/ {{ number_format($enrollment->total_cost, 2) }}</h3>
                    </div>
                    <div class="col-md-4 border-end">
                        <small class="text-muted d-block text-uppercase">Pagado</small>
                        <h3 class="fw-bold mb-0 text-success">S/ {{ number_format($enrollment->total_paid, 2) }}</h3>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block text-uppercase">Saldo Pendiente</small>
                        <h3 class="fw-bold mb-0 text-danger">S/ {{ number_format($enrollment->balance, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Plan de Cuotas -->
    <div class="col-md-7">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Cronograma de Pagos</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Vencimiento</th>
                                <th>Monto</th>
                                <th>Pagado</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrollment->installments as $inst)
                            <tr>
                                <td>{{ $inst->installment_number }}</td>
                                <td>{{ $inst->due_date->format('d/m/Y') }}</td>
                                <td class="fw-bold">S/ {{ number_format($inst->amount, 2) }}</td>
                                <td class="text-success">S/ {{ number_format($inst->paid_amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $inst->status_color }}">
                                        {{ ucfirst($inst->installment_status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No se ha definido un plan de cuotas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial de Pagos -->
    <div class="col-md-5">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Historial de Pagos</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($enrollment->recordedPayments as $payment)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold">S/ {{ number_format($payment->amount, 2) }}</div>
                                <small class="text-muted">{{ $payment->payment_date->format('d/m/Y') }} - {{ $payment->method_display }}</small>
                            </div>
                            <span class="badge bg-{{ $payment->status_color }}">{{ ucfirst($payment->payment_status) }}</span>
                        </div>
                        @if($payment->reference)
                            <small class="d-block mt-1 text-muted">Ref: {{ $payment->reference }}</small>
                        @endif
                    </li>
                    @empty
                    <li class="list-group-item text-center py-4 text-muted">No hay pagos registrados.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
