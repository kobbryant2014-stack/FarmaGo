@extends('layouts.admin')

@section('title', 'Detalle de venta - FarmaGo')

@section('content')
<section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h1>Venta #{{ $venta->id }}</h1>
        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Volver</a>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <p><strong>Cliente:</strong> {{ $venta->cliente->nombre ?? 'Publico general' }}</p>
                <p><strong>Fecha:</strong> {{ $venta->fecha?->format('d/m/Y H:i') }}</p>
                <p><strong>Subtotal:</strong> S/ {{ number_format((float) $venta->subtotal, 2) }}</p>
                <p><strong>IGV:</strong> S/ {{ number_format((float) $venta->igv_total, 2) }}</p>
                <p><strong>Total:</strong> S/ {{ number_format((float) $venta->total, 2) }}</p>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Lote</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>IGV</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->producto->nombre ?? '-' }}</td>
                                <td>{{ $detalle->lote->numero_lote ?? '-' }}</td>
                                <td>{{ number_format($detalle->cantidad, 2) }}</td>
                                <td>S/ {{ number_format((float) $detalle->precio_unitario, 2) }}</td>
                                <td>S/ {{ number_format((float) $detalle->igv, 2) }}</td>
                                <td>S/ {{ number_format((float) $detalle->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-right">
                <form method="POST" action="{{ route('ventas.facturacion.emitir', $venta) }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="tipo_comprobante" value="03">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-invoice-dollar"></i> Boleta electronica
                    </button>
                </form>
                <form method="POST" action="{{ route('ventas.facturacion.emitir', $venta) }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="tipo_comprobante" value="01">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-file-invoice"></i> Factura electronica
                    </button>
                </form>
                <a class="btn btn-secondary" href="{{ route('ventas.comprobante', $venta) }}">Ver comprobante</a>
            </div>
        </div>
    </div>
</section>
@endsection
