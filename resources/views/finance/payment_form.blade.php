@extends('layouts.app')

@section('title', 'Registrar Pago')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('enrollment_list') }}">Matrículas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('enrollment_detail', $enrollment) }}">{{ $enrollment->enrollment_code }}</a></li>
            <li class="breadcrumb-item active">Registrar Pago</li>
        </ol>
    </nav>
    <h1>Registrar Pago</h1>
    <p class="text-muted">Alumno: {{ $enrollment->alumno->full_name }}</p>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('payment_store', $enrollment) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="amount" class="form-label">Monto a Pagar</label>
                        <div class="input-group">
                            <span class="input-group-text">S/</span>
                            <input type="number" step="0.01" name="amount" id="amount" class="form-control form-control-lg" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="method" class="form-label">Método de Pago</label>
                        <select name="method" id="method" class="form-select" required>
                            @foreach($methods as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="payment_date" class="form-label">Fecha del Pago</label>
                        <input type="datetime-local" name="payment_date" id="payment_date" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="reference" class="form-label">Referencia / Nro. Operación</label>
                        <input type="text" name="reference" id="reference" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notas Adicionales</label>
                        <textarea name="notes" id="notes" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('enrollment_detail', $enrollment) }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Registrar Pago
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card shadow-sm bg-light">
            <div class="card-body">
                <h5 class="card-title">Cuotas Pendientes</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Cuota</th>
                                <th>Vencimiento</th>
                                <th class="text-end">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalPending = 0; @endphp
                            @foreach($enrollment->installments->whereIn('installment_status', ['pending', 'partial', 'overdue']) as $inst)
                            <tr>
                                <td>#{{ $inst->installment_number }}</td>
                                <td>{{ $inst->due_date->format('d/m/Y') }}</td>
                                <td class="text-end fw-bold">{{ number_format($inst->balance, 2) }}</td>
                            </tr>
                            @php $totalPending += $inst->balance; @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-dark">
                                <td colspan="2">TOTAL PENDIENTE</td>
                                <td class="text-end">S/ {{ number_format($totalPending, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
