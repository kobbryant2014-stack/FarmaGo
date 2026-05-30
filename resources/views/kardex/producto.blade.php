@extends('layouts.admin')

@section('title', 'Kardex por producto - FarmaGo')

@section('content')
<section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h1>Kardex: {{ $producto->nombre }}</h1>
        <a href="{{ route('kardex.index') }}" class="btn btn-secondary">Volver</a>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Lote</th>
                            <th>Origen</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Saldo</th>
                            <th>Motivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movimientos as $movimiento)
                            <tr>
                                <td>{{ $movimiento->fecha_movimiento?->format('d/m/Y H:i') }}</td>
                                <td>{{ $movimiento->lote->numero_lote ?? '-' }}</td>
                                <td>{{ $movimiento->origen }}</td>
                                <td>{{ ucfirst($movimiento->tipo) }}</td>
                                <td>{{ number_format($movimiento->cantidad, 2) }}</td>
                                <td>{{ number_format($movimiento->stock_acumulado, 2) }}</td>
                                <td>{{ $movimiento->motivo }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted">Sin movimientos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
