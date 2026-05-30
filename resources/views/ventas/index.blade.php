@extends('layouts.admin')

@section('title', 'Ventas - FarmaGo')

@section('content')
<section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-cash-register"></i> Ventas</h1>
        <a href="{{ route('ventas.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nueva venta</a>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <form method="GET" class="form-inline">
                    <input class="form-control mr-2" type="date" name="fecha" value="{{ $fecha }}">
                    <button class="btn btn-outline-primary">Filtrar</button>
                </form>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ventas as $venta)
                            <tr>
                                <td>{{ $venta->id }}</td>
                                <td>{{ $venta->cliente->nombre ?? 'Publico general' }}</td>
                                <td>{{ $venta->fecha?->format('d/m/Y H:i') }}</td>
                                <td>S/ {{ number_format((float) $venta->total, 2) }}</td>
                                <td>{{ ucfirst($venta->estado) }}</td>
                                <td class="text-right">
                                    <a class="btn btn-sm btn-info" href="{{ route('ventas.show', $venta) }}"><i class="fas fa-eye"></i></a>
                                    <a class="btn btn-sm btn-secondary" href="{{ route('ventas.comprobante', $venta) }}"><i class="fas fa-print"></i></a>
                                    @if($venta->puedeAnularse())
                                        <form class="d-inline" method="POST" action="{{ route('ventas.destroy', $venta) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Anular venta?')"><i class="fas fa-ban"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted">No hay ventas registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">{{ $ventas->links() }}</div>
        </div>
    </div>
</section>
@endsection
