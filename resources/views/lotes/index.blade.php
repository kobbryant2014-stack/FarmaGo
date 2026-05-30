@extends('layouts.admin')

@section('title', 'Lotes - FarmaGo')

@section('content')
<section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-boxes"></i> Lotes</h1>
        <a href="{{ route('lotes.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo lote</a>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <form method="GET" class="form-inline">
                    <input class="form-control mr-2" name="buscar" value="{{ $buscar }}" placeholder="Lote o producto">
                    <button class="btn btn-outline-primary">Buscar</button>
                </form>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Lote</th>
                            <th>Producto</th>
                            <th>Vencimiento</th>
                            <th>Stock actual</th>
                            <th>Estado</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lotes as $lote)
                            <tr class="{{ $lote->estaVencido() ? 'table-danger' : ($lote->proximoAVencer(30) ? 'table-warning' : '') }}">
                                <td>{{ $lote->numero_lote }}</td>
                                <td>{{ $lote->producto->nombre ?? '-' }}</td>
                                <td>{{ $lote->fecha_vencimiento?->format('d/m/Y') }}</td>
                                <td>{{ number_format($lote->stock_actual, 2) }}</td>
                                <td>{{ ucfirst($lote->estado ?? 'activo') }}</td>
                                <td class="text-right">
                                    <a class="btn btn-sm btn-info" href="{{ route('lotes.show', $lote) }}"><i class="fas fa-eye"></i></a>
                                    <a class="btn btn-sm btn-warning" href="{{ route('lotes.edit', $lote) }}"><i class="fas fa-edit"></i></a>
                                    <form class="d-inline" method="POST" action="{{ route('lotes.destroy', $lote) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Desactivar lote?')"><i class="fas fa-ban"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted">No hay lotes registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">{{ $lotes->links() }}</div>
        </div>
    </div>
</section>
@endsection
