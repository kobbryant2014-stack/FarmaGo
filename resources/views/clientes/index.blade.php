@extends('layouts.admin')

@section('title', 'Clientes - FarmaGo')

@section('content')
<section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-users"></i> Clientes</h1>
        <a href="{{ route('clientes.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo cliente</a>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <form method="GET" class="form-inline">
                    <input class="form-control mr-2" name="buscar" value="{{ $buscar }}" placeholder="DNI, RUC o nombre">
                    <button class="btn btn-outline-primary">Buscar</button>
                </form>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Documento</th>
                            <th>Telefono</th>
                            <th>Estado</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clientes as $cliente)
                            <tr>
                                <td>{{ $cliente->nombre }}</td>
                                <td>{{ $cliente->tipo_documento }} {{ $cliente->documento }}</td>
                                <td>{{ $cliente->telefono ?? '-' }}</td>
                                <td>{{ $cliente->activo ? 'Activo' : 'Inactivo' }}</td>
                                <td class="text-right">
                                    <a class="btn btn-sm btn-info" href="{{ route('clientes.show', $cliente) }}"><i class="fas fa-eye"></i></a>
                                    <a class="btn btn-sm btn-warning" href="{{ route('clientes.edit', $cliente) }}"><i class="fas fa-edit"></i></a>
                                    <form class="d-inline" method="POST" action="{{ route('clientes.destroy', $cliente) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Desactivar cliente?')"><i class="fas fa-ban"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">No hay clientes registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">{{ $clientes->links() }}</div>
        </div>
    </div>
</section>
@endsection
