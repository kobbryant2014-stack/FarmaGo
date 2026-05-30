@extends('layouts.admin')

@section('title', 'Detalle de producto - FarmaGo')

@section('content')
<section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h1>{{ $producto->nombre }}</h1>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Volver</a>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-box"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Stock disponible</span>
                        <span class="info-box-number">{{ number_format($producto->stock_disponible, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Stock minimo</span>
                        <span class="info-box-number">{{ $producto->stock_minimo }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-tag"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Precio venta</span>
                        <span class="info-box-number">S/ {{ number_format((float) $producto->precio_venta, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3 class="card-title">Lotes asociados</h3></div>
            <div class="card-body table-responsive p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Lote</th>
                            <th>Vencimiento</th>
                            <th>Stock actual</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($producto->lotes as $lote)
                            <tr>
                                <td>{{ $lote->numero_lote }}</td>
                                <td>{{ $lote->fecha_vencimiento?->format('d/m/Y') }}</td>
                                <td>{{ number_format($lote->stock_actual, 2) }}</td>
                                <td>{{ ucfirst($lote->estado ?? 'activo') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">Sin lotes registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
