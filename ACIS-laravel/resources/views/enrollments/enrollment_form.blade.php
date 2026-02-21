@extends('layouts.app')

@section('title', 'Nueva Matrícula')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('enrollment_list') }}">Matrículas</a></li>
            <li class="breadcrumb-item active">Nueva</li>
        </ol>
    </nav>
    <h1>Registrar Nueva Matrícula</h1>
</div>

<form action="{{ route('enrollment_store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">1. Alumno</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="alumno_search" class="form-label">Buscar Alumno</label>
                        <div class="input-group">
                            <input type="text" id="alumno_search" class="form-control" placeholder="Nombre, apellido o DNI/Pasaporte...">
                            <button type="button" class="btn btn-outline-secondary" id="btn_search_alumno"><i class="bi bi-search"></i></button>
                        </div>
                        <select name="alumno_id" id="alumno_id" class="form-select mt-2" required>
                            <option value="">Seleccione un alumno...</option>
                        </select>
                    </div>
                    <div id="alumno_info" class="p-3 bg-light rounded d-none">
                        <div class="row small">
                            <div class="col-6"><span class="text-muted">Documento:</span> <span id="info_doc"></span></div>
                            <div class="col-6"><span class="text-muted">Nombre:</span> <span id="info_name"></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">2. Producto / Programa</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="producto_search" class="form-label">Buscar Producto</label>
                        <div class="input-group">
                            <input type="text" id="producto_search" class="form-control" placeholder="SKU o descripción...">
                            <button type="button" class="btn btn-outline-secondary" id="btn_search_producto"><i class="bi bi-search"></i></button>
                        </div>
                        <select name="product_id" id="product_id" class="form-select mt-2" required>
                            <option value="">Seleccione un producto...</option>
                        </select>
                    </div>
                    <div id="producto_info" class="p-3 bg-light rounded d-none">
                        <div class="row small">
                            <div class="col-6"><span class="text-muted">Precio:</span> <span id="info_price"></span></div>
                            <div class="col-6"><span class="text-muted">Tipo:</span> <span id="info_type"></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('enrollment_list') }}" class="btn btn-lg btn-outline-secondary">Cancelar</a>
        <button type="submit" class="btn btn-lg btn-primary" id="btn_save" disabled>
            <i class="bi bi-check-circle"></i> Crear Pre-Matrícula
        </button>
    </div>
</form>

@push('scripts')
<script>
document.getElementById('btn_search_alumno').addEventListener('click', function() {
    const q = document.getElementById('alumno_search').value;
    if (q.length < 3) return alert('Ingrese al menos 3 caracteres');
    
    fetch(`{{ route('alumno_search') }}?q=${q}`)
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('alumno_id');
            select.innerHTML = '<option value="">Seleccione un alumno...</option>';
            data.forEach(a => {
                select.innerHTML += `<option value="${a.id}" data-doc="${a.numero_documento}" data-name="${a.nombre} ${a.apellido_paterno}">${a.nombre} ${a.apellido_paterno} ${a.apellido_materno} (${a.numero_documento})</option>`;
            });
        });
});

document.getElementById('btn_search_producto').addEventListener('click', function() {
    const q = document.getElementById('producto_search').value;
    if (q.length < 3) return alert('Ingrese al menos 3 caracteres');
    
    fetch(`{{ route('producto_search') }}?q=${q}`)
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('product_id');
            select.innerHTML = '<option value="">Seleccione un producto...</option>';
            data.forEach(p => {
                select.innerHTML += `<option value="${p.id}" data-price="${p.precio}" data-type="${p.tipo_producto}">${p.sku} - ${p.descripcion}</option>`;
            });
        });
});

document.getElementById('alumno_id').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    if (this.value) {
        document.getElementById('info_doc').textContent = opt.dataset.doc;
        document.getElementById('info_name').textContent = opt.dataset.name;
        document.getElementById('alumno_info').classList.remove('d-none');
    } else {
        document.getElementById('alumno_info').classList.add('d-none');
    }
    checkFormStatus();
});

document.getElementById('product_id').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    if (this.value) {
        document.getElementById('info_price').textContent = opt.dataset.price;
        document.getElementById('info_type').textContent = opt.dataset.type;
        document.getElementById('producto_info').classList.remove('d-none');
    } else {
        document.getElementById('producto_info').classList.add('d-none');
    }
    checkFormStatus();
});

function checkFormStatus() {
    const a = document.getElementById('alumno_id').value;
    const p = document.getElementById('product_id').value;
    document.getElementById('btn_save').disabled = !(a && p);
}
</script>
@endpush
@endsection
