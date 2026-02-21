<?php

use App\Http\Controllers\{AcademicController, AlumnoController, DashboardController, EnrollmentController, FinanceController, UserController, WebController};
use Illuminate\Support\Facades\Route;

/* ══════════ Públicas ══════════ */
Route::get('/', [WebController::class, 'home'])->name('home');
Route::get('/catalogo', [WebController::class, 'catalogue'])->name('catalogue');
Route::get('/carrito', [WebController::class, 'cart'])->name('cart');

/* ══════════ Autenticadas ══════════ */
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile',  [UserController::class, 'profile'])->name('profile');
    Route::put('/profile',  [UserController::class, 'updateProfile'])->name('profile.update');

    /* ── Usuarios ── */
    Route::get('/users',                  [UserController::class, 'index'])->name('user_list');
    Route::get('/users/create',           [UserController::class, 'create'])->name('user_create');
    Route::post('/users',                 [UserController::class, 'store'])->name('user_store');
    Route::get('/users/{user}/edit',      [UserController::class, 'edit'])->name('user_update');
    Route::put('/users/{user}',           [UserController::class, 'update'])->name('user_update.save');
    Route::get('/audit',                  [UserController::class, 'auditLog'])->name('audit_log');

    /* ── Alumnos ── */
    Route::get('/alumnos',                    [AlumnoController::class, 'index'])->name('alumno_list');
    Route::get('/alumnos/create',             [AlumnoController::class, 'create'])->name('alumno_create');
    Route::post('/alumnos',                   [AlumnoController::class, 'store'])->name('alumno_store');
    Route::get('/alumnos/{alumno}',           [AlumnoController::class, 'show'])->name('alumno_detail');
    Route::get('/alumnos/{alumno}/edit',      [AlumnoController::class, 'edit'])->name('alumno_update');
    Route::put('/alumnos/{alumno}',           [AlumnoController::class, 'update'])->name('alumno_update.save');

    /* ── Profesiones ── */
    Route::get('/profesiones',                     [AcademicController::class, 'profesionIndex'])->name('profesion_list');
    Route::get('/profesiones/create',              [AcademicController::class, 'profesionCreate'])->name('profesion_create');
    Route::post('/profesiones',                    [AcademicController::class, 'profesionStore'])->name('profesion_store');
    Route::get('/profesiones/{profesion}/edit',     [AcademicController::class, 'profesionEdit'])->name('profesion_update');
    Route::put('/profesiones/{profesion}',          [AcademicController::class, 'profesionUpdate'])->name('profesion_update.save');

    /* ── Distritos ── */
    Route::get('/distritos',                    [AcademicController::class, 'distritoIndex'])->name('distrito_list');
    Route::get('/distritos/create',             [AcademicController::class, 'distritoCreate'])->name('distrito_create');
    Route::post('/distritos',                   [AcademicController::class, 'distritoStore'])->name('distrito_store');
    Route::get('/distritos/{distrito}/edit',     [AcademicController::class, 'distritoEdit'])->name('distrito_update');
    Route::put('/distritos/{distrito}',          [AcademicController::class, 'distritoUpdate'])->name('distrito_update.save');

    /* ── Academic: Categorías ── */
    Route::get('/academic/categorias',                     [AcademicController::class, 'categoriaIndex'])->name('categoria_list');
    Route::get('/academic/categorias/create',              [AcademicController::class, 'categoriaCreate'])->name('categoria_create');
    Route::post('/academic/categorias',                    [AcademicController::class, 'categoriaStore'])->name('categoria_store');
    Route::get('/academic/categorias/{categoria}/edit',     [AcademicController::class, 'categoriaEdit'])->name('categoria_update');
    Route::put('/academic/categorias/{categoria}',          [AcademicController::class, 'categoriaUpdate'])->name('categoria_update.save');

    /* ── Academic: Temas ── */
    Route::get('/academic/temas',                  [AcademicController::class, 'temaIndex'])->name('tema_list');
    Route::get('/academic/temas/create',           [AcademicController::class, 'temaCreate'])->name('tema_create');
    Route::post('/academic/temas',                 [AcademicController::class, 'temaStore'])->name('tema_store');
    Route::get('/academic/temas/{tema}/edit',       [AcademicController::class, 'temaEdit'])->name('tema_update');
    Route::put('/academic/temas/{tema}',            [AcademicController::class, 'temaUpdate'])->name('tema_update.save');

    /* ── Academic: Productos ── */
    Route::get('/academic/productos',                      [AcademicController::class, 'productoIndex'])->name('producto_list');
    Route::get('/academic/productos/create',               [AcademicController::class, 'productoCreate'])->name('producto_create');
    Route::post('/academic/productos',                     [AcademicController::class, 'productoStore'])->name('producto_store');
    Route::get('/academic/productos/{producto}/edit',       [AcademicController::class, 'productoEdit'])->name('producto_update');
    Route::put('/academic/productos/{producto}',            [AcademicController::class, 'productoUpdate'])->name('producto_update.save');

    /* ── Academic: Etiquetas ── */
    Route::get('/academic/etiquetas',                      [AcademicController::class, 'etiquetaIndex'])->name('etiqueta_list');
    Route::get('/academic/etiquetas/create',               [AcademicController::class, 'etiquetaCreate'])->name('etiqueta_create');
    Route::post('/academic/etiquetas',                     [AcademicController::class, 'etiquetaStore'])->name('etiqueta_store');
    Route::get('/academic/etiquetas/{etiqueta}/edit',       [AcademicController::class, 'etiquetaEdit'])->name('etiqueta_update');
    Route::put('/academic/etiquetas/{etiqueta}',            [AcademicController::class, 'etiquetaUpdate'])->name('etiqueta_update.save');

    /* ── Periodos ── */
    Route::get('/periods',                     [AcademicController::class, 'periodIndex'])->name('period_list');
    Route::get('/periods/create',              [AcademicController::class, 'periodCreate'])->name('period_create');
    Route::post('/periods',                    [AcademicController::class, 'periodStore'])->name('period_store');
    Route::get('/periods/{period}/edit',        [AcademicController::class, 'periodEdit'])->name('period_update');
    Route::put('/periods/{period}',             [AcademicController::class, 'periodUpdate'])->name('period_update.save');

    /* ── Programas ── */
    Route::get('/programs',                     [AcademicController::class, 'programIndex'])->name('program_list');
    Route::get('/programs/create',              [AcademicController::class, 'programCreate'])->name('program_create');
    Route::post('/programs',                    [AcademicController::class, 'programStore'])->name('program_store');
    Route::get('/programs/{program}',           [AcademicController::class, 'programShow'])->name('program_detail');
    Route::get('/programs/{program}/edit',       [AcademicController::class, 'programEdit'])->name('program_update');
    Route::put('/programs/{program}',            [AcademicController::class, 'programUpdate'])->name('program_update.save');

    /* ── Docentes ── */
    Route::get('/teachers',                     [AcademicController::class, 'teacherIndex'])->name('teacher_list');
    Route::get('/teachers/create',              [AcademicController::class, 'teacherCreate'])->name('teacher_create');
    Route::post('/teachers',                    [AcademicController::class, 'teacherStore'])->name('teacher_store');
    Route::get('/teachers/{teacher}/edit',       [AcademicController::class, 'teacherEdit'])->name('teacher_update');
    Route::put('/teachers/{teacher}',            [AcademicController::class, 'teacherUpdate'])->name('teacher_update.save');
    Route::get('/teachers/api/users',           [AcademicController::class, 'teacherUserSearch'])->name('teacher_user_search');
    Route::get('/teachers/api/productos',       [AcademicController::class, 'teacherProductoSearch'])->name('teacher_producto_search');

    /* ── Matrículas ── */
    Route::get('/enrollments',                        [EnrollmentController::class, 'index'])->name('enrollment_list');
    Route::get('/enrollments/create',                 [EnrollmentController::class, 'create'])->name('enrollment_create');
    Route::post('/enrollments',                       [EnrollmentController::class, 'store'])->name('enrollment_store');
    Route::get('/enrollments/{enrollment}',           [EnrollmentController::class, 'show'])->name('enrollment_detail');
    Route::post('/enrollments/{enrollment}/confirm',  [EnrollmentController::class, 'confirm'])->name('enrollment_confirm');
    Route::post('/enrollments/{enrollment}/cancel',   [EnrollmentController::class, 'cancel'])->name('enrollment_cancel');
    Route::get('/enrollments/{enrollment}/pdf',      [EnrollmentController::class, 'downloadFicha'])->name('enrollment_pdf');
    Route::get('/enrollments/api/alumnos',            [EnrollmentController::class, 'alumnoSearch'])->name('enrollment_alumno_search');
    Route::get('/enrollments/api/productos',          [EnrollmentController::class, 'productoSearch'])->name('enrollment_producto_search');

    /* ── Finanzas ── */
    Route::get('/payments',                                              [FinanceController::class, 'paymentIndex'])->name('payment_list');
    Route::post('/payments/{payment}/validate',                          [FinanceController::class, 'paymentValidate'])->name('payment_validate');
    Route::post('/payments/{payment}/reject',                            [FinanceController::class, 'paymentReject'])->name('payment_reject');
    Route::get('/enrollments/{enrollment}/financial',                     [FinanceController::class, 'studentFinancialDetail'])->name('student_financial_detail');
    Route::get('/enrollments/{enrollment}/payments/create',               [FinanceController::class, 'paymentCreate'])->name('payment_create');
    Route::post('/enrollments/{enrollment}/payments',                     [FinanceController::class, 'paymentStore'])->name('payment_store');
    Route::match(['get', 'post'], '/enrollments/{enrollment}/installments/plan', [FinanceController::class, 'installmentPlan'])->name('installment_plan');
    Route::post('/enrollments/{enrollment}/moodle/enable',               [FinanceController::class, 'enableMoodle'])->name('enable_moodle');
    Route::get('/alumno/matricula/{enrollment}/pagar',                    [FinanceController::class, 'alumnoPayEnrollment'])->name('alumno_pay_enrollment');
});

/* ── Webhook (sin auth) ── */
Route::post('/webhooks/culqi', [FinanceController::class, 'culqiWebhook'])->name('culqi_webhook');

require __DIR__.'/auth.php';
