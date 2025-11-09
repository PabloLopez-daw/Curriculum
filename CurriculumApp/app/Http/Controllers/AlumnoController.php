<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlumnoController extends Controller
{
    public function index()
    {
        $alumnos = Alumno::paginate(10);
        return view('alumnos.index', compact('alumnos'));
    }

    public function create()
    {
        return view('alumnos.create');
    }

    public function store(Request $request)
    {

    $data = $request->all();

    $nombre = $request->input('nombre');
    $apellidos = $request->input('apellidos');
    $baseName = Str::slug($nombre . ' ' . $apellidos);
    $timestamp = now()->format('Ymd_His'); 
    $baseFileName = "{$baseName}_{$timestamp}";

 
    if ($request->hasFile('fotografia')) {
        $foto = $request->file('fotografia');
        $extension = $foto->getClientOriginalExtension();

      
        $fileName = "{$baseFileName}.{$extension}";

     
        $pathFoto = $foto->storeAs('alumnos/fotos', $fileName, 'public');

        $data['fotografia'] = $pathFoto; 
    }


    if ($request->hasFile('curriculum')) {
        $pdf = $request->file('curriculum');
        $extensionPdf = $pdf->getClientOriginalExtension();

    
        $fileNamePdf = "{$baseFileName}_cv.{$extensionPdf}";
        $pathPrivate = $pdf->storeAs('alumnos/curriculums', $fileNamePdf, 'local');
        $pathPublic = $pdf->storeAs('alumnos/curriculums', $fileNamePdf, 'public');

        $data['curriculum_path_private'] = $pathPrivate;
        $data['curriculum_path_public'] = $pathPublic;
    }

        if ($request->hasFile('fotografia')) {
            $data['fotografia'] = $request->file('fotografia')->store('fotos', 'public');
        }

        if ($request->hasFile('curriculum')) {
            $data['curriculum_path_private'] = $request->file('curriculum')->store('curriculums', 'local');
            $data['curriculum_path_public'] = $request->file('curriculum')->store('curriculums', 'public');
        }

        Alumno::create($data);
        return redirect()->route('alumnos.index')->with('success', 'Alumno creado correctamente.');
    }

    public function show(Alumno $alumno)
    {
        return view('alumnos.show', compact('alumno'));
    }

    public function edit(Alumno $alumno)
    {
        return view('alumnos.edit', compact('alumno'));
    }

    public function update(Request $request, Alumno $alumno)
    {
        $request->validate([
            'nombre' => 'required',
            'apellidos' => 'required',
            'correo' => 'required|email|unique:alumnos,correo,' . $alumno->id,
        ]);

        $data = $request->all();

        if ($request->hasFile('fotografia')) {
            if ($alumno->fotografia) {
                Storage::disk('public')->delete($alumno->fotografia);
            }
            $data['fotografia'] = $request->file('fotografia')->store('fotos', 'public');
        }

        if ($request->hasFile('curriculum')) {
            if ($alumno->curriculum_path_private) {
                Storage::disk('private')->delete($alumno->curriculum_path_private);
            }
            if ($alumno->curriculum_path_public) {
                Storage::disk('public')->delete($alumno->curriculum_path_public);
            }
            $data['curriculum_path_private'] = $request->file('curriculum')->store('curriculums', 'private');
            $data['curriculum_path_public'] = $request->file('curriculum')->store('curriculums', 'public');
        }

        $alumno->update($data);
        return redirect()->route('alumnos.index')->with('success', 'Alumno actualizado correctamente.');
    }

    public function destroy(Alumno $alumno)
    {
        if ($alumno->fotografia) {
            Storage::disk('public')->delete($alumno->fotografia);
        }
        if ($alumno->curriculum_path_private) {
            Storage::disk('private')->delete($alumno->curriculum_path_private);
        }
        if ($alumno->curriculum_path_public) {
            Storage::disk('public')->delete($alumno->curriculum_path_public);
        }

        $alumno->delete();
        return redirect()->route('alumnos.index')->with('success', 'Alumno eliminado correctamente.');
    }

    public function descargarCV(Alumno $alumno)
    {
        return Storage::disk('local')->download($alumno->curriculum_path_private);
    }
}
