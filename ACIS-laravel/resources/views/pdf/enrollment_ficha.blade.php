<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ficha de Matrícula - {{ $enrollment->enrollment_code }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .section { margin-bottom: 20px; }
        .section-title { font-weight: bold; background: #eee; padding: 5px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #777; }
        .qr-placeholder { float: right; border: 1px solid #ccc; padding: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>FICHA DE MATRÍCULA</h1>
        <p>ACIS - Academia de Capacitación e Innovación en Salud</p>
    </div>

    <div class="section">
        <div class="qr-placeholder">
            VERIFICACIÓN UUID:<br>
            <strong>{{ $enrollment->verification_uuid }}</strong>
        </div>
        <p><strong>CÓDIGO:</strong> {{ $enrollment->enrollment_code }}</p>
        <p><strong>FECHA:</strong> {{ $enrollment->created_at->format('d/m/Y') }}</p>
    </div>

    <div class="section">
        <div class="section-title">DATOS DEL ALUMNO</div>
        <table>
            <tr><th width="30%">Apellidos y Nombres:</th><td>{{ $enrollment->alumno->full_name }}</td></tr>
            <tr><th>Documento:</th><td>{{ $enrollment->alumno->tipo_documento }}: {{ $enrollment->alumno->numero_documento }}</td></tr>
            <tr><th>Email:</th><td>{{ $enrollment->alumno->email }}</td></tr>
            <tr><th>Teléfono:</th><td>{{ $enrollment->alumno->telefono }}</td></tr>
            <tr><th>Profesión:</th><td>{{ $enrollment->alumno->profesion->nombre ?? 'N/A' }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">PROGRAMA / PRODUCTO</div>
        <table>
            <tr><th width="30%">Descripción:</th><td>{{ $enrollment->product->descripcion }}</td></tr>
            <tr><th>Tipo:</th><td>{{ ucfirst($enrollment->product->tipo_producto) }}</td></tr>
            <tr><th>Costo Total:</th><td>S/ {{ number_format($enrollment->total_cost, 2) }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">PLAN DE PAGOS</div>
        <table>
            <thead>
                <tr>
                    <th>Cuota</th>
                    <th>Vencimiento</th>
                    <th>Monto</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enrollment->installments as $inst)
                <tr>
                    <td>Cuota #{{ $inst->installment_number }}</td>
                    <td>{{ $inst->due_date->format('d/m/Y') }}</td>
                    <td>S/ {{ number_format($inst->amount, 2) }}</td>
                    <td>{{ ucfirst($inst->installment_status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        Este documento es una constancia de pre-matrícula / matrícula generada por el sistema ACIS.
    </div>
</body>
</html>
