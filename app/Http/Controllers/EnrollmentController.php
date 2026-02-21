<?php

namespace App\Http\Controllers;

use App\Models\{Alumno, Enrollment, Producto};
use App\Services\PdfService;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }
    public function index(Request $request)
    {
        $enrollments = Enrollment::with(['alumno', 'product'])
            ->when($request->search, function ($q, $s) {
                $q->where('enrollment_code', 'like', "%{$s}%")
                  ->orWhereHas('alumno', fn($aq) => $aq->where('nombre', 'like', "%{$s}%")->orWhere('apellido_paterno', 'like', "%{$s}%"));
            })
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('enrollments.enrollment_list', compact('enrollments'));
    }

    public function create()
    {
        return view('enrollments.enrollment_form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'alumno_id'  => 'required|exists:alumnos,id',
            'product_id' => 'required|exists:productos,id',
        ]);

        $producto = Producto::findOrFail($data['product_id']);
        $totalCost = $producto->isDiplomado() ? $producto->getTotalDiplomado() : (float) $producto->precio;

        $enrollment = Enrollment::create([
            'alumno_id'         => $data['alumno_id'],
            'product_id'        => $data['product_id'],
            'total_cost'        => $totalCost,
            'enrollment_status' => Enrollment::STATUS_PRE_REGISTERED,
            'enrollment_code'   => 'MAT-' . now()->format('Ymd') . '-' . str_pad(Enrollment::count() + 1, 4, '0', STR_PAD_LEFT),
            'created_by'        => $request->user()->id,
            'asesor_comercial'  => $request->user()->id,
        ]);

        return redirect()->route('enrollment_detail', $enrollment)->with('success', 'Matrícula creada.');
    }

    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['alumno', 'product', 'contract', 'installments', 'recordedPayments', 'asesor']);
        return view('enrollments.enrollment_detail', compact('enrollment'));
    }

    public function confirm(Enrollment $enrollment)
    {
        $enrollment->update(['enrollment_status' => Enrollment::STATUS_REGISTERED]);
        return redirect()->route('enrollment_detail', $enrollment)->with('success', 'Matrícula confirmada.');
    }

    public function cancel(Request $request, Enrollment $enrollment)
    {
        $request->validate(['cancellation_reason' => 'required|string']);
        $enrollment->update([
            'enrollment_status'   => Enrollment::STATUS_CANCELED,
            'cancellation_reason' => $request->cancellation_reason,
            'canceled_at'         => now(),
            'canceled_by'         => $request->user()->id,
        ]);
        return redirect()->route('enrollment_detail', $enrollment)->with('success', 'Matrícula anulada.');
    }
    public function downloadFicha(Enrollment $enrollment)
    {
        $enrollment->load(['alumno.profesion', 'product', 'installments']);
        return $this->pdfService->generate(
            'pdf.enrollment_ficha',
            compact('enrollment'),
            "Ficha-{$enrollment->enrollment_code}.pdf",
            true
        );
    }

    /* ── API búsquedas ── */

    public function alumnoSearch(Request $request)
    {
        $q = $request->get('q', '');
        $alumnos = Alumno::where('estado', 'activo')
            ->where(fn($query) => $query->where('nombre', 'like', "%{$q}%")
                ->orWhere('apellido_paterno', 'like', "%{$q}%")
                ->orWhere('numero_documento', 'like', "%{$q}%"))
            ->limit(20)
            ->get(['id', 'nombre', 'apellido_paterno', 'apellido_materno', 'numero_documento']);
        return response()->json($alumnos);
    }

    public function productoSearch(Request $request)
    {
        $q = $request->get('q', '');
        $productos = Producto::active()
            ->where(fn($query) => $query->where('sku', 'like', "%{$q}%")->orWhere('descripcion', 'like', "%{$q}%"))
            ->limit(20)
            ->get(['id', 'sku', 'descripcion', 'precio', 'tipo_producto']);
        return response()->json($productos);
    }
}
