@extends('layouts.web')

@section('title', 'Pagar Matrícula')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <h2 class="fw-bold mb-4">Finalizar Compra</h2>
                    <div class="mb-4">
                        <label class="text-muted small d-block">Producto</label>
                        <span class="fs-5 fw-bold">{{ $enrollment->product->descripcion }}</span>
                    </div>
                    <div class="mb-4">
                        <label class="text-muted small d-block">Monto a Pagar</label>
                        <span class="fs-2 fw-bold text-primary">S/ {{ number_format($enrollment->total_cost, 2) }}</span>
                    </div>

                    <hr class="my-4">

                    <form action="#" id="payment-form">
                        <div class="d-grid gap-3">
                            <button type="button" id="btn-pay" class="btn btn-primary btn-lg py-3 fw-bold">
                                <i class="bi bi-credit-card me-2"></i> Pagar con Culqi
                            </button>
                            <p class="text-center text-muted small mt-2">
                                <i class="bi bi-shield-check"></i> Pago 100% seguro procesado por Culqi.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Culqi Integration --}}
<script src="https://checkout.culqi.com/js/v4"></script>
<script>
    Culqi.settings({
        title: 'ACIS - Sistema Académico',
        currency: 'PEN',
        amount: {{ $enrollment->total_cost * 100 }},
        pk: '{{ config('services.culqi.public_key') }}'
    });

    const btnPay = document.getElementById('btn-pay');
    btnPay.addEventListener('click', function (e) {
        Culqi.open();
        e.preventDefault();
    });

    function culqi() {
        if (Culqi.token) {
            const token = Culqi.token.id;
            const email = Culqi.token.email;
            
            // Aquí se enviaría el token al servidor para procesar el cargo
            console.log('Token generado: ' + token);
            alert('En un entorno real, aquí enviaríamos el token al servidor.');
        } else {
            console.log(Culqi.error);
        }
    }
</script>
@endsection
