@extends('layouts.app')

@section('title', 'Gestionar Plan de Cuotas')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('enrollment_list') }}">Matrículas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('enrollment_detail', $enrollment) }}">{{ $enrollment->enrollment_code }}</a></li>
            <li class="breadcrumb-item active">Plan de Cuotas</li>
        </ol>
    </nav>
    <h1>Gestionar Plan de Cuotas</h1>
    <p class="text-muted">Costo Total: <strong>S/ {{ number_format($enrollment->total_cost, 2) }}</strong></p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('installment_plan', $enrollment) }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="installments_table">
                            <thead class="table-light">
                                <tr>
                                    <th width="10%">#</th>
                                    <th>Monto</th>
                                    <th>Vencimiento</th>
                                    <th width="10%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($enrollment->installments as $i => $inst)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        <input type="number" step="0.01" name="installments[{{ $i }}][amount]" class="form-control inst-amount" value="{{ $inst->amount }}" required>
                                    </td>
                                    <td>
                                        <input type="date" name="installments[{{ $i }}][due_date]" class="form-control" value="{{ $inst->due_date->format('Y-m-d') }}" required>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <input type="number" step="0.01" name="installments[0][amount]" class="form-control inst-amount" value="{{ $enrollment->total_cost }}" required>
                                    </td>
                                    <td>
                                        <input type="date" name="installments[0][due_date]" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <button type="button" class="btn btn-outline-primary" id="add_row">
                            <i class="bi bi-plus-lg"></i> Agregar Cuota
                        </button>
                        <div class="text-end">
                            <div class="fs-5 fw-bold">Total Plan: S/ <span id="plan_total">0.00</span></div>
                            <div id="diff_msg" class="small"></div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('enrollment_detail', $enrollment) }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary" id="btn_save">
                            <i class="bi bi-save"></i> Guardar Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm border-warning">
            <div class="card-body">
                <h5 class="card-title text-warning"><i class="bi bi-exclamation-triangle"></i> Importante</h5>
                <ul class="small mb-0">
                    <li>Al guardar un nuevo plan, se eliminará el plan anterior.</li>
                    <li>La suma de las cuotas debería coincidir con el costo total (S/ {{ number_format($enrollment->total_cost, 2) }}).</li>
                    <li>Si el plan no coincide, el sistema permitirá guardarlo pero mostrará una advertencia.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let rowCount = {{ $enrollment->installments->count() ?: 1 }};
const totalTarget = {{ $enrollment->total_cost }};

document.getElementById('add_row').addEventListener('click', function() {
    const tbody = document.querySelector('#installments_table tbody');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td>${rowCount + 1}</td>
        <td><input type="number" step="0.01" name="installments[${rowCount}][amount]" class="form-control inst-amount" required></td>
        <td><input type="date" name="installments[${rowCount}][due_date]" class="form-control" required></td>
        <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="bi bi-trash"></i></button></td>
    `;
    tbody.appendChild(newRow);
    rowCount++;
    updateTotal();
});

document.querySelector('#installments_table').addEventListener('click', function(e) {
    if (e.target.closest('.remove-row')) {
        e.target.closest('tr').remove();
        updateTotal();
    }
});

document.querySelector('#installments_table').addEventListener('input', function(e) {
    if (e.target.classList.contains('inst-amount')) {
        updateTotal();
    }
});

function updateTotal() {
    let total = 0;
    document.querySelectorAll('.inst-amount').forEach(input => {
        total += parseFloat(input.value || 0);
    });
    
    document.getElementById('plan_total').textContent = total.toFixed(2);
    const diff = total - totalTarget;
    const msg = document.getElementById('diff_msg');
    
    if (Math.abs(diff) < 0.01) {
        msg.innerHTML = '<span class="text-success"><i class="bi bi-check-circle"></i> Coincide con el total</span>';
    } else {
        msg.innerHTML = `<span class="text-danger"><i class="bi bi-exclamation-circle"></i> Diferencia: S/ ${diff.toFixed(2)}</span>`;
    }
}

updateTotal();
</script>
@endpush
@endsection
