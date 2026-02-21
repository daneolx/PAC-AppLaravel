@extends('layouts.app')

@section('title', 'Pagos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Validación de Pagos</h1>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Alumno</th>
                        <th>Monto</th>
                        <th>Método</th>
                        <th>Referencia</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="fw-bold">{{ $payment->enrollment->alumno->full_name }}</div>
                            <small class="text-muted">{{ $payment->enrollment->enrollment_code }}</small>
                        </td>
                        <td class="fw-bold">{{ number_format($payment->amount, 2) }}</td>
                        <td>{{ \App\Models\Payment::METHODS[$payment->method] ?? $payment->method }}</td>
                        <td><small>{{ $payment->reference }}</small></td>
                        <td>
                            @php
                                $statusColors = ['pending' => 'warning', 'validated' => 'success', 'rejected' => 'danger'];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$payment->payment_status] ?? 'secondary' }}">
                                {{ ucfirst($payment->payment_status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            @if($payment->payment_status === 'pending')
                            <div class="btn-group btn-group-sm">
                                <form action="{{ route('payment_validate', $payment) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success" onclick="return confirm('¿Validar este pago?')"><i class="bi bi-check-lg"></i></button>
                                </form>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $payment->id }}">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>

                            <!-- Modal Rechazo -->
                            <div class="modal fade" id="rejectModal{{ $payment->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('payment_reject', $payment) }}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Rechazar Pago</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <div class="mb-3">
                                                    <label class="form-label">Motivo de Rechazo</label>
                                                    <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-danger">Rechazar Pago</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No hay pagos pendientes de validación.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $payments->links() }}
    </div>
</div>
@endsection
