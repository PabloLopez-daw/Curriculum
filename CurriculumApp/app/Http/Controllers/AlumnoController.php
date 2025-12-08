<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

class AlumnoController extends Controller
{
    public function index()
    {
        try {
            $alumnos = Alumno::all();
            return view('alumnos.index', compact('alumnos'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error al cargar la lista de alumnos.');
        }
    }

    public function create()
    {
        try {
            return view('alumnos.create');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'No se pudo cargar el formulario.');
        }
    }

    public function store(Request $request)
    {
        
        $validatedData = $request->validate([
            'nombre'           => 'required|string|max:255',
            'apellidos'        => 'required|string|max:255',
            'correo'           => 'required|email|unique:alumnos,correo', 
            'telefono'         => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'nota_media'       => 'nullable|numeric|between:0,10',
            'experiencia'      => 'nullable|string',
            'formacion'        => 'nullable|string',
            'habilidades'      => 'nullable|string',
            'fotografia'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'curriculum'       => 'nullable|mimes:pdf|max:5120', 
        ]);

        try {
            // Usamos $validatedData o $request->all(), pero ya sabemos que son válidos.
            $data = $request->all();

            $nombre = $request->input('nombre');
            $apellidos = $request->input('apellidos');
            
            // Generación del nombre de archivo personalizado
            $baseName = Str::slug($nombre . ' ' . $apellidos);
            $timestamp = now()->format('Ymd_His'); 
            $baseFileName = "{$baseName}_{$timestamp}";

            // Subida de Fotografía
            if ($request->hasFile('fotografia')) {
                $foto = $request->file('fotografia');
                $extension = $foto->getClientOriginalExtension();
                $fileName = "{$baseFileName}.{$extension}";

                $pathFoto = $foto->storeAs('alumnos/fotos', $fileName, 'public');
                $data['fotografia'] = $pathFoto; 
            }

            // Subida de Curriculum
            if ($request->hasFile('curriculum')) {
                $pdf = $request->file('curriculum');
                $extensionPdf = $pdf->getClientOriginalExtension();
                $fileNamePdf = "{$baseFileName}_cv.{$extensionPdf}";
                
                // Guardar en local y public
                $pathPrivate = $pdf->storeAs('alumnos/curriculums', $fileNamePdf, 'local');
                $pathPublic = $pdf->storeAs('alumnos/curriculums', $fileNamePdf, 'public');

                $data['curriculum_path_private'] = $pathPrivate;
                $data['curriculum_path_public'] = $pathPublic;
            }

            Alumno::create($data);
            return redirect()->route('alumnos.index')->with('success', 'Alumno creado correctamente.');

        } catch (Exception $e) {
            // Es buena práctica loguear el error para depurar: Log::error($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al crear el alumno.');
        }
    }

    public function show(Alumno $alumno)
    {
        try {
            return view('alumnos.show', compact('alumno'));
        } catch (Exception $e) {
            return redirect()->route('alumnos.index')->with('error', 'No se pudo cargar la información del alumno.');
        }
    }

    public function edit(Alumno $alumno)
    {
        try {
            return view('alumnos.edit', compact('alumno'));
        } catch (Exception $e) {
            return redirect()->route('alumnos.index')->with('error', 'No se pudo cargar el formulario de edición.');
        }
    }

    public function update(Request $request, Alumno $alumno)
    {
        // 1. VALIDACIÓN DE DATOS PARA ACTUALIZACIÓN
        $request->validate([
            'nombre'           => 'required|string|max:255',
            'apellidos'        => 'required|string|max:255',
            'correo'           => 'required|email|unique:alumnos,correo,' . $alumno->id, 
            'telefono'         => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'nota_media'       => 'nullable|numeric|between:0,10',
            'experiencia'      => 'nullable|string',
            'formacion'        => 'nullable|string',
            'habilidades'      => 'nullable|string',
            'fotografia'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'curriculum'       => 'nullable|mimes:pdf|max:5120',
        ]);

        try {
            $data = $request->all();

            // Lógica de actualización de Fotografía
            if ($request->hasFile('fotografia')) {
                if ($alumno->fotografia) {
                    Storage::disk('public')->delete($alumno->fotografia);
                }
                
                $data['fotografia'] = $request->file('fotografia')->store('alumnos/fotos', 'public');
            }

            if ($request->hasFile('curriculum')) {
                if ($alumno->curriculum_path_private) {
                    Storage::disk('local')->delete($alumno->curriculum_path_private);
                }
                if ($alumno->curriculum_path_public) {
                    Storage::disk('public')->delete($alumno->curriculum_path_public);
                }
                
                // Subir nuevos
                $data['curriculum_path_private'] = $request->file('curriculum')->store('alumnos/curriculums', 'local');
                $data['curriculum_path_public'] = $request->file('curriculum')->store('alumnos/curriculums', 'public');
            }

            $alumno->update($data);
            return redirect()->route('alumnos.index')->with('success', 'Alumno actualizado correctamente.');

        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al actualizar el alumno.');
        }
    }

    public function destroy(Alumno $alumno)
    {
        try {
            if ($alumno->fotografia) {
                Storage::disk('public')->delete($alumno->fotografia);
            }

            if ($alumno->curriculum_path_public) {
                Storage::disk('public')->delete($alumno->curriculum_path_public);
            }

            if ($alumno->curriculum_path_private) {
                 Storage::disk('local')->delete($alumno->curriculum_path_private);
            }

            $alumno->delete();
            return redirect()->route('alumnos.index')->with('success', 'Alumno eliminado correctamente.');

        } catch (Exception $e) {
            return redirect()->route('alumnos.index')->with('error', 'Ocurrió un error al eliminar el alumno.');
        }
    }
}