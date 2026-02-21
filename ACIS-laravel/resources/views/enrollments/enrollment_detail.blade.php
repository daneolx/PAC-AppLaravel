@extends('layouts.app')

@section('title', 'Detalle de Matrícula - ' . $enrollment->enrollment_code)

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('enrollment_list') }}">Matrículas</a></li>
                <li class="breadcrumb-item active">{{ $enrollment->enrollment_code }}</li>
            </ol>
        </nav>
        <h1>Matrícula: {{ $enrollment->enrollment_code }}</h1>
    </div>
    <div class="d-flex gap-2">
        @if($enrollment->enrollment_status === 'pre_registered')
        <form action="{{ route('enrollment_confirm', $enrollment) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> Confirmar Matrícula</button>
        </form>
        @endif
        
        @if($enrollment->enrollment_status !== 'canceled')
        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
            <i class="bi bi-x-circle"></i> Anular
        </button>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Información del Alumno</h5>
            </div>
            <div class="card-body">
                <div class="mb-2"><strong>Nombre:</strong> {{ $enrollment->alumno->full_name }}</div>
                <div class="mb-2"><strong>Documento:</strong> {{ $enrollment->alumno->numero_documento }}</div>
                <div class="mb-2"><strong>Email:</strong> {{ $enrollment->alumno->email }}</div>
                <div class="mb-2"><strong>Teléfono:</strong> {{ $enrollment->alumno->celular }}</div>
                <hr>
                <div class="mb-0 small text-muted">Registrado por: {{ $enrollment->asesor->name ?? 'Sistema' }}</div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Producto / Programa</h5>
            </div>
            <div class="card-body">
                <div class="mb-2"><strong>SKU:</strong> {{ $enrollment->product->sku }}</div>
                <div class="mb-2"><strong>Descripción:</strong> {{ $enrollment->product->descripcion }}</div>
                <div class="mb-2"><strong>Costo Total:</strong> {{ number_format($enrollment->total_cost, 2) }}</div>
                <div class="mb-0"><strong>Estado:</strong> 
                    <span class="badge bg-{{ $enrollment->enrollment_status === 'registered' ? 'success' : ($enrollment->enrollment_status === 'canceled' ? 'danger' : 'info') }}">
                        {{ ucfirst(str_replace('_', ' ', $enrollment->enrollment_status)) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <ul class="nav nav-tabs mb-3" id="enrollmentTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="finance-tab" data-bs-toggle="tab" data-bs-target="#finance" type="button" role="tab">Finanzas</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contract-tab" data-bs-toggle="tab" data-bs-target="#contract" type="button" role="tab">Contrato</button>
            </li>
        </ul>
        <div class="tab-content" id="enrollmentTabsContent">
            <div class="tab-pane fade show active" id="finance" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Plan de Pagos</h5>
                            <div>
                                <a href="{{ route('installment_plan', $enrollment) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-calendar-range"></i> Gestionar Plan
                                </a>
                                <a href="{{ route('payment_create', $enrollment) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-cash"></i> Registrar Pago
                                </a>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
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
                                        <td>{{ number_format($inst->amount, 2) }}</td>
                                        <td>{{ number_format($inst->paid_amount, 2) }}</td>
                                        <td>
                                            @php
                                                $instColors = ['pending' => 'secondary', 'partial' => 'warning', 'paid' => 'success', 'overdue' => 'danger'];
                                            @endphp
                                            <span class="badge bg-{{ $instColors[$inst->installment_status] ?? 'light' }}">
                                                {{ ucfirst($inst->installment_status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-3 text-muted">No se ha generado un plan de pagos.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="contract" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        @if($enrollment->contract)
                            <div class="mb-3"><i class="bi bi-file-earmark-pdf text-danger fs-1"></i></div>
                            <h5>Contrato Generado</h5>
                            <p class="text-muted">UUID: {{ $enrollment->contract->verification_uuid }}</p>
                            <a href="#" class="btn btn-outline-danger"><i class="bi bi-download"></i> Descargar PDF</a>
                        @else
                            <div class="mb-3"><i class="bi bi-file-earmark-x text-muted fs-1"></i></div>
                            <h5>Sin Contrato</h5>
                            <p class="text-muted">Aún no se ha generado el contrato para esta matrícula.</p>
                            <button class="btn btn-primary"><i class="bi bi-magic"></i> Generar Contrato</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Anular -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('enrollment_cancel', $enrollment) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Anular Matrícula</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label">Motivo de Anulación</label>
                        <textarea name="cancellation_reason" id="cancellation_reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Anulación</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
