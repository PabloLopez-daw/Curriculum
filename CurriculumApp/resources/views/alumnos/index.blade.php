@extends('base.plantilla')

@section('title', 'Listado de alumnos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Alumnos</h1>
    <a href="{{ route('alumnos.create') }}" class="btn btn-primary">Nuevo alumno</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Foto</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Nota media</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    @foreach($alumnos as $alumno)
        <tr>
            <td style="width:80px;">
                @if($alumno->fotografia)
                    <img src="{{ asset('storage/'.$alumno->fotografia) }}" alt="foto" class="img-thumbnail" style="max-width:70px;">
                @else
                    <div class="text-muted">–</div>
                @endif
            </td>
            <td>{{ $alumno->nombre }} {{ $alumno->apellidos }}</td>
            <td>{{ $alumno->correo }}</td>
            <td>{{ $alumno->nota_media ?? '—' }}</td>
            <td>
                <a href="{{ route('alumnos.show', $alumno->id) }}" class="btn btn-sm btn-info">Ver</a>
                <a href="{{ route('alumnos.edit', $alumno->id) }}" class="btn btn-sm btn-warning">Editar</a>

                <form action="{{ route('alumnos.destroy', $alumno) }}" method="POST" style="display:inline-block" onsubmit="return confirm('¿Eliminar alumno?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Borrar</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

{{ $alumnos->links() }}

@endsection
