@extends('layouts.admin')

@section('title', 'Compras - FarmaGo')

@section('content')
<section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-shopping-bag"></i> Compras</h1>
        <a href="{{ route('compras.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nueva compra</a>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <form method="GET" class="form-inline">
                    <input class="form-control mr-2" name="buscar" value="{{ $buscar }}" placeholder="Proveedor o RUC">
                    <button class="btn btn-outline-primary">Buscar</button>
                </form>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($compras as $compra)
                            <tr>
                                <td>{{ $compra->id }}</td>
                                <td>{{ $compra->proveedor->nombre ?? '-' }}</td>
                                <td>{{ $compra->fecha?->format('d/m/Y H:i') }}</td>
                                <td>S/ {{ number_format((float) $compra->total, 2) }}</td>
                                <td>{{ ucfirst($compra->estado) }}</td>
                                <td class="text-right">
                                    <a class="btn btn-sm btn-info" href="{{ route('compras.show', $compra) }}"><i class="fas fa-eye"></i></a>
                                    @if($compra->puedeAnularse())
                                        <form class="d-inline" method="POST" action="{{ route('compras.destroy', $compra) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Anular compra?')"><i class="fas fa-ban"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted">No hay compras registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">{{ $compras->links() }}</div>
        </div>
    </div>
</section>
@endsection
