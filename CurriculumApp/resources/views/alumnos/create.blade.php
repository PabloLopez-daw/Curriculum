@extends('base.plantilla')

@section('content')
<div class="container mt-4">
    <h2>🧑‍💻 Crear nuevo alumno</h2>

    <a href="{{ route('alumnos.index') }}" class="btn btn-secondary mb-3">⬅️ Volver</a>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>¡Error!</strong> Corrige los campos marcados.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('alumnos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Apellidos</label>
                <input type="text" name="apellidos" class="form-control" value="{{ old('apellidos') }}" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Correo</label>
                <input type="email" name="correo" class="form-control" value="{{ old('correo') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Nota media</label>
                <input type="number" name="nota_media" step="0.01" class="form-control" value="{{ old('nota_media') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Fotografía</label>
                <input type="file" name="fotografia" class="form-control" accept="image/*">
            </div>
            <div class="col-md-4">
                <label class="form-label">Currículum (PDF)</label>
                <input type="file" name="curriculum" class="form-control" accept="application/pdf">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Experiencia</label>
            <textarea name="experiencia" rows="3" class="form-control">{{ old('experiencia') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Formación</label>
            <textarea name="formacion" rows="3" class="form-control">{{ old('formacion') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Habilidades</label>
            <textarea name="habilidades" rows="3" class="form-control">{{ old('habilidades') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">💾 Guardar alumno</button>
    </form>
</div>
@endsection
