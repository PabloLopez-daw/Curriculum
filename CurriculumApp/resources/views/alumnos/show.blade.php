@extends('base.plantilla')

@section('title', 'Ficha alumno')

@section('content')
<h1>{{ $alumno->nombre }} {{ $alumno->apellidos }}</h1>

<div class="row">
    <div class="col-md-4">
        @if($alumno->fotografia)
            <img src="{{ asset('storage/'.$alumno->fotografia) }}" class="img-fluid img-thumbnail" alt="foto">
        @endif
    </div>
    <div class="col-md-8">
        <p><strong>Correo:</strong> {{ $alumno->correo }}</p>
        <p><strong>Teléfono:</strong> {{ $alumno->telefono }}</p>
        <p><strong>Fecha de nacimiento:</strong> {{ $alumno->fecha_nacimiento?->format('d/m/Y') }}</p>
        <p><strong>Nota media:</strong> {{ $alumno->nota_media }}</p>
        <p><strong>Experiencia:</strong><br>{{ $alumno->experiencia }}</p>
        <p><strong>Formación:</strong><br>{{ $alumno->formacion }}</p>
        <p><strong>Habilidades:</strong><br>{{ $alumno->habilidades }}</p>

        @if($alumno->curriculum_path_public)
            <a href="{{ asset('storage/'.$alumno->curriculum_path_public) }}" class="btn btn-outline-primary" target="_blank">Ver copia pública PDF</a>
        @endif

    </div>
</div>

@endsection
