<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Profesion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlumnoController extends Controller
{
    public function index(Request $request)
    {
        $alumnos = Alumno::with('profesion')
            ->when($request->search, function ($q, $s) {
                $q->where('nombre', 'like', "%{$s}%")
                  ->orWhere('apellido_paterno', 'like', "%{$s}%")
                  ->orWhere('numero_documento', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            })
            ->orderBy('apellido_paterno')
            ->paginate(20);

        return view('enrollments.alumno_list', compact('alumnos'));
    }

    public function create()
    {
        return view('enrollments.alumno_form', [
            'alumno'      => new Alumno(),
            'profesiones' => Profesion::orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateAlumno($request);
        $data['created_by'] = $request->user()->id;
        Alumno::create($data);

        return redirect()->route('alumno_list')->with('success', 'Alumno registrado correctamente.');
    }

    public function show(Alumno $alumno)
    {
        $alumno->load(['direcciones.distrito', 'profesion', 'enrollments.product']);
        return view('enrollments.alumno_detail', compact('alumno'));
    }

    public function edit(Alumno $alumno)
    {
        return view('enrollments.alumno_form', [
            'alumno'      => $alumno,
            'profesiones' => Profesion::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Alumno $alumno)
    {
        $data = $this->validateAlumno($request, $alumno);
        $alumno->update($data);

        return redirect()->route('alumno_list')->with('success', 'Alumno actualizado correctamente.');
    }

    private function validateAlumno(Request $request, ?Alumno $alumno = null): array
    {
        return $request->validate([
            'tipo_documento'    => ['required', Rule::in(array_keys(Alumno::TIPOS_DOCUMENTO))],
            'numero_documento'  => 'required|string|max:20',
            'nombre'            => 'required|string|max:100',
            'apellido_paterno'  => 'required|string|max:100',
            'apellido_materno'  => 'nullable|string|max:100',
            'fecha_nacimiento'  => 'nullable|date',
            'email'             => ['required', 'email', Rule::unique('alumnos')->ignore($alumno)],
            'telefono'          => 'nullable|string|max:20',
            'discapacidad'      => 'required|in:SI,NO',
            'grado_academico'   => 'nullable|string|max:200',
            'profesion_id'      => 'nullable|exists:profesiones,id',
            'colegiatura'       => 'nullable|string|max:100',
            'estado'            => 'required|in:activo,inactivo',
        ]);
    }
}
