@extends('layouts.admin')

@section('title', 'Detalle de compra - FarmaGo')

@section('content')
<section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h1>Compra #{{ $compra->id }}</h1>
        <a href="{{ route('compras.index') }}" class="btn btn-secondary">Volver</a>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <p><strong>Proveedor:</strong> {{ $compra->proveedor->nombre ?? '-' }}</p>
                <p><strong>Fecha:</strong> {{ $compra->fecha?->format('d/m/Y H:i') }}</p>
                <p><strong>Total:</strong> S/ {{ number_format((float) $compra->total, 2) }}</p>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Lote</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($compra->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->producto->nombre ?? '-' }}</td>
                                <td>{{ $detalle->lote->numero_lote ?? '-' }}</td>
                                <td>{{ number_format($detalle->cantidad, 2) }}</td>
                                <td>S/ {{ number_format((float) $detalle->precio_unitario, 2) }}</td>
                                <td>S/ {{ number_format((float) $detalle->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
