@extends('layouts.admin')

@section('title', 'Proveedores - FarmaGo')

@section('content')
<section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-truck"></i> Proveedores</h1>
        <a href="{{ route('proveedores.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo proveedor</a>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <form method="GET" class="form-inline">
                    <input class="form-control mr-2" name="buscar" value="{{ $buscar }}" placeholder="RUC o razon social">
                    <button class="btn btn-outline-primary">Buscar</button>
                </form>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Proveedor</th>
                            <th>RUC</th>
                            <th>Contacto</th>
                            <th>Estado</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proveedores as $proveedor)
                            <tr>
                                <td>{{ $proveedor->nombre }}</td>
                                <td>{{ $proveedor->ruc }}</td>
                                <td>{{ $proveedor->contacto ?? '-' }}</td>
                                <td>{{ $proveedor->activo ? 'Activo' : 'Inactivo' }}</td>
                                <td class="text-right">
                                    <a class="btn btn-sm btn-info" href="{{ route('proveedores.show', $proveedor) }}"><i class="fas fa-eye"></i></a>
                                    <a class="btn btn-sm btn-warning" href="{{ route('proveedores.edit', $proveedor) }}"><i class="fas fa-edit"></i></a>
                                    <form class="d-inline" method="POST" action="{{ route('proveedores.destroy', $proveedor) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Desactivar proveedor?')"><i class="fas fa-ban"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">No hay proveedores registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">{{ $proveedores->links() }}</div>
        </div>
    </div>
</section>
@endsection
