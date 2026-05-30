@extends('layouts.admin')

@section('title', 'Productos - FarmaGo')

@section('content')
<section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-pills"></i> Productos</h1>
        <a href="{{ route('productos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo producto
        </a>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <form class="form-inline" method="GET">
                    <input class="form-control mr-2" name="buscar" value="{{ $buscar }}" placeholder="Buscar por nombre, codigo, DCI o registro">
                    <button class="btn btn-outline-primary">Buscar</button>
                </form>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categoria</th>
                            <th>Precio</th>
                            <th>Stock disponible</th>
                            <th>Estado</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $producto)
                            <tr>
                                <td>
                                    <strong>{{ $producto->nombre }}</strong><br>
                                    <small>{{ $producto->codigo_barra ?? $producto->codigo_interno ?? 'Sin codigo' }}</small>
                                </td>
                                <td>{{ $producto->categoria->nombre ?? '-' }}</td>
                                <td>S/ {{ number_format((float) $producto->precio_venta, 2) }}</td>
                                <td>
                                    <span class="badge {{ $producto->estaEnStockBajo() ? 'badge-danger' : 'badge-success' }}">
                                        {{ number_format($producto->stock_disponible, 2) }}
                                    </span>
                                </td>
                                <td>{{ ucfirst($producto->estado ?? 'activo') }}</td>
                                <td class="text-right">
                                    <a class="btn btn-sm btn-info" href="{{ route('productos.show', $producto) }}"><i class="fas fa-eye"></i></a>
                                    <a class="btn btn-sm btn-warning" href="{{ route('productos.edit', $producto) }}"><i class="fas fa-edit"></i></a>
                                    <form class="d-inline" method="POST" action="{{ route('productos.destroy', $producto) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Desactivar producto?')">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted">No hay productos registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $productos->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
