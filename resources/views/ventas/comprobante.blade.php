@extends('layouts.admin')

@section('title', 'Comprobante de venta - FarmaGo')

@section('content')
<section class="content-header no-print">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h1>Comprobante {{ $tipo }} #{{ $venta->id }}</h1>
        <div>
            <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Imprimir</button>
            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="invoice p-3 mb-3">
            <div class="row">
                <div class="col-12">
                    <h4>
                        <i class="fas fa-pills"></i> FarmaGo
                        <small class="float-right">Fecha: {{ $venta->fecha?->format('d/m/Y H:i') }}</small>
                    </h4>
                </div>
            </div>
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    Comprobante<br>
                    <strong>{{ $tipo }} #{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</strong>
                </div>
                <div class="col-sm-4 invoice-col">
                    Cliente<br>
                    <strong>{{ $venta->cliente->nombre ?? 'Publico general' }}</strong>
                </div>
                <div class="col-sm-4 invoice-col">
                    Cajero<br>
                    <strong>{{ $venta->usuario->name ?? '-' }}</strong>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Lote</th>
                                <th>Cant.</th>
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
            </div>
            <div class="row">
                <div class="col-6"></div>
                <div class="col-6">
                    <table class="table">
                        <tr><th>Subtotal</th><td>S/ {{ number_format((float) $venta->subtotal, 2) }}</td></tr>
                        <tr><th>Descuento</th><td>S/ {{ number_format((float) $venta->descuento_total, 2) }}</td></tr>
                        <tr><th>IGV</th><td>S/ {{ number_format((float) $venta->igv_total, 2) }}</td></tr>
                        <tr><th>Total</th><td><strong>S/ {{ number_format((float) $venta->total, 2) }}</strong></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
@media print {
    .main-sidebar, .main-header, .main-footer, .no-print { display: none !important; }
    .content-wrapper { margin-left: 0 !important; }
}
</style>
@endpush
