<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ACIS') - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .sidebar{min-height:100vh;background:#212529}.sidebar a{color:#adb5bd;text-decoration:none;padding:8px 16px;display:block;font-size:.9rem}.sidebar a:hover,.sidebar a.active{color:#fff;background:#343a40}.sidebar .nav-header{color:#6c757d;font-size:.75rem;text-transform:uppercase;padding:12px 16px 4px}
    </style>
    @stack('styles')
</head>
<body>
<div class="d-flex">
    <nav class="sidebar d-none d-md-block" style="width:250px;flex-shrink:0">
        <div class="p-3"><a href="{{ route('dashboard') }}" class="text-white fs-5 fw-bold text-decoration-none"><i class="bi bi-mortarboard"></i> ACIS</a></div>
        <div class="nav-header">Gesti&oacute;n</div>
        <a href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="{{ route('alumno_list') }}"><i class="bi bi-people"></i> Alumnos</a>
        <a href="{{ route('enrollment_list') }}"><i class="bi bi-clipboard-check"></i> Matr&iacute;culas</a>
        <a href="{{ route('payment_list') }}"><i class="bi bi-cash-stack"></i> Pagos</a>
        <div class="nav-header">Acad&eacute;mico</div>
        <a href="{{ route('producto_list') }}"><i class="bi bi-box"></i> Productos</a>
        <a href="{{ route('categoria_list') }}"><i class="bi bi-tags"></i> Categor&iacute;as</a>
        <a href="{{ route('tema_list') }}"><i class="bi bi-journal-text"></i> Temas</a>
        <a href="{{ route('etiqueta_list') }}"><i class="bi bi-tag"></i> Etiquetas</a>
        <a href="{{ route('program_list') }}"><i class="bi bi-diagram-3"></i> Programas</a>
        <a href="{{ route('period_list') }}"><i class="bi bi-calendar-range"></i> Periodos</a>
        <a href="{{ route('teacher_list') }}"><i class="bi bi-person-badge"></i> Docentes</a>
        <div class="nav-header">Cat&aacute;logos</div>
        <a href="{{ route('profesion_list') }}"><i class="bi bi-briefcase"></i> Profesiones</a>
        <a href="{{ route('distrito_list') }}"><i class="bi bi-geo-alt"></i> Distritos</a>
        <div class="nav-header">Administraci&oacute;n</div>
        <a href="{{ route('user_list') }}"><i class="bi bi-person-gear"></i> Usuarios</a>
        <a href="{{ route('audit_log') }}"><i class="bi bi-journal-code"></i> Auditor&iacute;a</a>
    </nav>
    <div class="flex-grow-1" style="min-height:100vh">
        <nav class="navbar navbar-light bg-white border-bottom px-3">
            <div class="container-fluid">
                <span class="navbar-brand d-md-none">ACIS</span>
                <div class="ms-auto d-flex align-items-center">
                    @auth
                    <span class="me-3 text-muted small">{{ auth()->user()->full_name }}</span>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-person-circle"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile') }}">Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><form method="POST" action="{{ route('logout') }}">@csrf<button class="dropdown-item">Cerrar sesi&oacute;n</button></form></li>
                        </ul>
                    </div>
                    @endauth
                </div>
            </div>
        </nav>
        <div class="container-fluid p-4">
            @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
            @if(session('error'))<div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
            @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
            @yield('content')
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
