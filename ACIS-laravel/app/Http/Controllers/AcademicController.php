<?php

namespace App\Http\Controllers;

use App\Models\{AcademicPeriod, Categoria, Etiqueta, Producto, Program, Teacher, Tema, User};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AcademicController extends Controller
{
    /* ══════════ Categorías ══════════ */

    public function categoriaIndex(Request $request)
    {
        $items = Categoria::orderBy('name')->paginate(20);
        return view('academic.categoria_list', compact('items'));
    }

    public function categoriaCreate()
    {
        return view('academic.categoria_form', ['item' => new Categoria()]);
    }

    public function categoriaStore(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:200', 'status' => 'required|in:active,inactive']);
        $data['created_by'] = $request->user()->id;
        Categoria::create($data);
        return redirect()->route('categoria_list')->with('success', 'Categoría creada.');
    }

    public function categoriaEdit(Categoria $categoria)
    {
        return view('academic.categoria_form', ['item' => $categoria]);
    }

    public function categoriaUpdate(Request $request, Categoria $categoria)
    {
        $data = $request->validate(['name' => 'required|string|max:200', 'status' => 'required|in:active,inactive']);
        $categoria->update($data);
        return redirect()->route('categoria_list')->with('success', 'Categoría actualizada.');
    }

    /* ══════════ Temas ══════════ */

    public function temaIndex()
    {
        $items = Tema::orderBy('name')->paginate(20);
        return view('academic.tema_list', compact('items'));
    }

    public function temaCreate()
    {
        return view('academic.tema_form', ['item' => new Tema()]);
    }

    public function temaStore(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:200', 'status' => 'required|in:active,inactive']);
        $data['created_by'] = $request->user()->id;
        Tema::create($data);
        return redirect()->route('tema_list')->with('success', 'Tema creado.');
    }

    public function temaEdit(Tema $tema)
    {
        return view('academic.tema_form', ['item' => $tema]);
    }

    public function temaUpdate(Request $request, Tema $tema)
    {
        $data = $request->validate(['name' => 'required|string|max:200', 'status' => 'required|in:active,inactive']);
        $tema->update($data);
        return redirect()->route('tema_list')->with('success', 'Tema actualizado.');
    }

    /* ══════════ Etiquetas ══════════ */

    public function etiquetaIndex()
    {
        $items = Etiqueta::orderBy('name')->paginate(20);
        return view('academic.etiqueta_list', compact('items'));
    }

    public function etiquetaCreate()
    {
        return view('academic.etiqueta_form', ['item' => new Etiqueta()]);
    }

    public function etiquetaStore(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:100', 'status' => 'required|in:active,inactive']);
        $data['created_by'] = $request->user()->id;
        Etiqueta::create($data);
        return redirect()->route('etiqueta_list')->with('success', 'Etiqueta creada.');
    }

    public function etiquetaEdit(Etiqueta $etiqueta)
    {
        return view('academic.etiqueta_form', ['item' => $etiqueta]);
    }

    public function etiquetaUpdate(Request $request, Etiqueta $etiqueta)
    {
        $data = $request->validate(['name' => 'required|string|max:100', 'status' => 'required|in:active,inactive']);
        $etiqueta->update($data);
        return redirect()->route('etiqueta_list')->with('success', 'Etiqueta actualizada.');
    }

    /* ══════════ Productos ══════════ */

    public function productoIndex(Request $request)
    {
        $items = Producto::with(['tema', 'categoria'])
            ->when($request->search, fn($q, $s) => $q->where('sku', 'like', "%{$s}%")->orWhere('descripcion', 'like', "%{$s}%"))
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('academic.producto_list', compact('items'));
    }

    public function productoCreate()
    {
        return view('academic.producto_form', [
            'item'       => new Producto(),
            'categorias' => Categoria::active()->orderBy('name')->get(),
            'temas'      => Tema::active()->orderBy('name')->get(),
            'etiquetas'  => Etiqueta::active()->orderBy('name')->get(),
        ]);
    }

    public function productoStore(Request $request)
    {
        $data = $this->validateProducto($request);
        $data['created_by'] = $request->user()->id;

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto = Producto::create($data);
        $producto->etiquetas()->sync($request->input('etiquetas', []));

        return redirect()->route('producto_list')->with('success', 'Producto creado.');
    }

    public function productoEdit(Producto $producto)
    {
        return view('academic.producto_form', [
            'item'       => $producto,
            'categorias' => Categoria::active()->orderBy('name')->get(),
            'temas'      => Tema::active()->orderBy('name')->get(),
            'etiquetas'  => Etiqueta::active()->orderBy('name')->get(),
        ]);
    }

    public function productoUpdate(Request $request, Producto $producto)
    {
        $data = $this->validateProducto($request, $producto);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($data);
        $producto->etiquetas()->sync($request->input('etiquetas', []));

        return redirect()->route('producto_list')->with('success', 'Producto actualizado.');
    }

    /* ══════════ Periodos ══════════ */

    public function periodIndex()
    {
        $items = AcademicPeriod::orderByDesc('start_date')->paginate(20);
        return view('academic.period_list', compact('items'));
    }

    public function periodCreate()
    {
        return view('academic.period_form', ['item' => new AcademicPeriod()]);
    }

    public function periodStore(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'period_status' => 'required|in:open,closed',
            'status'        => 'required|in:active,inactive',
        ]);
        $data['created_by'] = $request->user()->id;
        AcademicPeriod::create($data);
        return redirect()->route('period_list')->with('success', 'Periodo creado.');
    }

    public function periodEdit(AcademicPeriod $period)
    {
        return view('academic.period_form', ['item' => $period]);
    }

    public function periodUpdate(Request $request, AcademicPeriod $period)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'period_status' => 'required|in:open,closed',
            'status'        => 'required|in:active,inactive',
        ]);
        $period->update($data);
        return redirect()->route('period_list')->with('success', 'Periodo actualizado.');
    }

    /* ══════════ Programas ══════════ */

    public function programIndex()
    {
        $items = Program::orderBy('name')->paginate(20);
        return view('academic.program_list', compact('items'));
    }

    public function programCreate()
    {
        return view('academic.program_form', ['item' => new Program(), 'modalities' => Program::MODALITIES]);
    }

    public function programStore(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:200',
            'code'         => 'required|string|max:20|unique:programs',
            'description'  => 'nullable|string',
            'modality'     => ['required', Rule::in(array_keys(Program::MODALITIES))],
            'duration'     => 'required|string|max:100',
            'base_cost'    => 'required|numeric|min:0',
            'max_students' => 'required|integer|min:1',
            'status'       => 'required|in:active,inactive',
        ]);
        $data['created_by'] = $request->user()->id;
        Program::create($data);
        return redirect()->route('program_list')->with('success', 'Programa creado.');
    }

    public function programShow(Program $program)
    {
        $program->load('enrollments.alumno');
        return view('academic.program_detail', compact('program'));
    }

    public function programEdit(Program $program)
    {
        return view('academic.program_form', ['item' => $program, 'modalities' => Program::MODALITIES]);
    }

    public function programUpdate(Request $request, Program $program)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:200',
            'code'         => ['required', 'string', 'max:20', Rule::unique('programs')->ignore($program)],
            'description'  => 'nullable|string',
            'modality'     => ['required', Rule::in(array_keys(Program::MODALITIES))],
            'duration'     => 'required|string|max:100',
            'base_cost'    => 'required|numeric|min:0',
            'max_students' => 'required|integer|min:1',
            'status'       => 'required|in:active,inactive',
        ]);
        $program->update($data);
        return redirect()->route('program_list')->with('success', 'Programa actualizado.');
    }

    /* ══════════ Docentes ══════════ */

    public function teacherIndex()
    {
        $items = Teacher::with('user')->orderByDesc('created_at')->paginate(20);
        return view('academic.teacher_list', compact('items'));
    }

    public function teacherCreate()
    {
        return view('academic.teacher_form', ['item' => new Teacher()]);
    }

    public function teacherStore(Request $request)
    {
        $data = $request->validate([
            'user_id'        => 'required|exists:users,id|unique:teachers,user_id',
            'specialization' => 'required|string|max:200',
            'bio'            => 'nullable|string',
            'status'         => 'required|in:active,inactive',
            'productos'      => 'nullable|array',
            'productos.*'    => 'exists:productos,id',
        ]);
        $data['created_by'] = $request->user()->id;
        $teacher = Teacher::create($data);
        $teacher->productos()->sync($request->input('productos', []));
        return redirect()->route('teacher_list')->with('success', 'Docente creado.');
    }

    public function teacherEdit(Teacher $teacher)
    {
        $teacher->load('productos');
        return view('academic.teacher_form', ['item' => $teacher]);
    }

    public function teacherUpdate(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'specialization' => 'required|string|max:200',
            'bio'            => 'nullable|string',
            'status'         => 'required|in:active,inactive',
            'productos'      => 'nullable|array',
            'productos.*'    => 'exists:productos,id',
        ]);
        $teacher->update($data);
        $teacher->productos()->sync($request->input('productos', []));
        return redirect()->route('teacher_list')->with('success', 'Docente actualizado.');
    }

    /* ══════════ API búsquedas ══════════ */

    public function teacherUserSearch(Request $request)
    {
        $q = $request->get('q', '');
        $users = User::where('role', User::ROLE_TEACHER)
            ->where(fn($query) => $query->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%"))
            ->limit(20)
            ->get(['id', 'name', 'email']);
        return response()->json($users);
    }

    public function teacherProductoSearch(Request $request)
    {
        $q = $request->get('q', '');
        $productos = Producto::active()
            ->where(fn($query) => $query->where('sku', 'like', "%{$q}%")->orWhere('descripcion', 'like', "%{$q}%"))
            ->limit(20)
            ->get(['id', 'sku', 'descripcion']);
        return response()->json($productos);
    }

    /* ══════════ Catálogos simples ══════════ */

    public function profesionIndex()
    {
        $items = \App\Models\Profesion::orderBy('nombre')->paginate(20);
        return view('enrollments.profesion_list', compact('items'));
    }

    public function profesionCreate()
    {
        return view('enrollments.profesion_form', ['item' => new \App\Models\Profesion()]);
    }

    public function profesionStore(Request $request)
    {
        $data = $request->validate(['nombre' => 'required|string|max:200', 'descripcion' => 'nullable|string']);
        \App\Models\Profesion::create($data);
        return redirect()->route('profesion_list')->with('success', 'Profesión creada.');
    }

    public function profesionEdit(\App\Models\Profesion $profesion)
    {
        return view('enrollments.profesion_form', ['item' => $profesion]);
    }

    public function profesionUpdate(Request $request, \App\Models\Profesion $profesion)
    {
        $data = $request->validate(['nombre' => 'required|string|max:200', 'descripcion' => 'nullable|string']);
        $profesion->update($data);
        return redirect()->route('profesion_list')->with('success', 'Profesión actualizada.');
    }

    public function distritoIndex()
    {
        $items = \App\Models\Distrito::orderBy('nombre')->paginate(20);
        return view('enrollments.distrito_list', compact('items'));
    }

    public function distritoCreate()
    {
        return view('enrollments.distrito_form', ['item' => new \App\Models\Distrito()]);
    }

    public function distritoStore(Request $request)
    {
        $data = $request->validate(['nombre' => 'required|string|max:150']);
        \App\Models\Distrito::create($data);
        return redirect()->route('distrito_list')->with('success', 'Distrito creado.');
    }

    public function distritoEdit(\App\Models\Distrito $distrito)
    {
        return view('enrollments.distrito_form', ['item' => $distrito]);
    }

    public function distritoUpdate(Request $request, \App\Models\Distrito $distrito)
    {
        $data = $request->validate(['nombre' => 'required|string|max:150']);
        $distrito->update($data);
        return redirect()->route('distrito_list')->with('success', 'Distrito actualizado.');
    }

    /* ══════════ Privado ══════════ */

    private function validateProducto(Request $request, ?Producto $producto = null): array
    {
        return $request->validate([
            'tipo_producto'      => ['required', Rule::in(array_keys(Producto::TIPOS))],
            'sku'                => ['required', 'string', 'max:50', Rule::unique('productos')->ignore($producto)],
            'descripcion'        => 'nullable|string',
            'tema_id'            => 'nullable|exists:temas,id',
            'categoria_id'       => 'nullable|exists:categorias,id',
            'fecha_inicio'       => 'nullable|date',
            'fecha_fin'          => 'nullable|date|after_or_equal:fecha_inicio',
            'modulo'             => 'nullable|string|max:200',
            'horas'              => 'required|integer|min:0',
            'creditos'           => 'required|integer|min:0',
            'precio'             => 'required|numeric|min:0',
            'meses'              => 'required|integer|min:1',
            'matricula'          => 'nullable|numeric|min:0',
            'mensualidad'        => 'nullable|numeric|min:0',
            'derecho'            => 'nullable|numeric|min:0',
            'imagen'             => 'nullable|image|max:2048',
            'moodle_course_id'   => 'nullable|integer',
            'requiere_membresia' => 'boolean',
            'status'             => 'required|in:active,inactive',
            'etiquetas'          => 'nullable|array',
            'etiquetas.*'        => 'exists:etiquetas,id',
        ]);
    }
}
