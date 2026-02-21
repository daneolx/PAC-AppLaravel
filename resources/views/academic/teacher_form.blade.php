@extends('layouts.app')

@section('title', ($item->id ? 'Editar' : 'Nuevo') . ' Docente')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('teacher_list') }}">Docentes</a></li>
            <li class="breadcrumb-item active">{{ $item->id ? 'Editar' : 'Nuevo' }}</li>
        </ol>
    </nav>
    <h1>{{ $item->id ? 'Editar' : 'Nuevo' }} Docente</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ $item->id ? route('teacher_update', $item) : route('teacher_store') }}" method="POST">
                    @csrf
                    @if($item->id) @method('PUT') @endif

                    @if(!$item->id)
                    <div class="mb-3">
                        <label for="user_search" class="form-label">Buscar Usuario (Rol Docente)</label>
                        <div class="input-group">
                            <input type="text" id="user_search" class="form-control" placeholder="Nombre o email...">
                            <button type="button" class="btn btn-outline-secondary" id="btn_search_user"><i class="bi bi-search"></i></button>
                        </div>
                        <select name="user_id" id="user_id" class="form-select mt-2" required>
                            <option value="">Seleccione un usuario...</option>
                        </select>
                        <small class="text-muted">Solo se muestran usuarios con rol 'teacher' que no son docentes aún.</small>
                    </div>
                    @else
                    <div class="mb-3">
                        <label class="form-label">Docente</label>
                        <input type="text" class="form-control" value="{{ $item->user->name }} ({{ $item->user->email }})" disabled>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="specialization" class="form-label">Especialización</label>
                        <input type="text" name="specialization" id="specialization" class="form-control" value="{{ old('specialization', $item->specialization) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="bio" class="form-label">Biografía</label>
                        <textarea name="bio" id="bio" class="form-control" rows="4">{{ old('bio', $item->bio) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Estado</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active" {{ old('status', $item->status) === 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="inactive" {{ old('status', $item->status) === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('teacher_list') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary" {{ !$item->id ? 'id=btn_save disabled' : '' }}>
                            <i class="bi bi-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(!$item->id)
@push('scripts')
<script>
document.getElementById('btn_search_user').addEventListener('click', function() {
    const q = document.getElementById('user_search').value;
    if (q.length < 3) return alert('Ingrese al menos 3 caracteres');
    
    fetch(`{{ route('teacher_user_search') }}?q=${q}`)
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('user_id');
            select.innerHTML = '<option value="">Seleccione un usuario...</option>';
            data.forEach(u => {
                select.innerHTML += `<option value="${u.id}">${u.name} (${u.email})</option>`;
            });
        });
});

document.getElementById('user_id').addEventListener('change', function() {
    document.getElementById('btn_save').disabled = !this.value;
});
</script>
@endpush
@endif
@endsection
