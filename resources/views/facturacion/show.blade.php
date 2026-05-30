@extends('layouts.admin')

@section('title', 'Comprobante electronico - FarmaGo')

@section('content')
<section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div>
            <h1>{{ $comprobante->tipo_comprobante === '01' ? 'Factura electronica' : 'Boleta electronica' }} {{ $comprobante->numero_completo }}</h1>
            <p class="text-muted mb-0">Estado SUNAT: {{ $comprobante->estado_sunat ?? $comprobante->estado }}</p>
        </div>
        <a href="{{ route('facturacion.index') }}" class="btn btn-secondary">Volver</a>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Emisor</strong>
                        <p class="mb-1">{{ $comprobante->empresa->razon_social ?? '-' }}</p>
                        <p class="mb-1">RUC: {{ $comprobante->empresa->ruc ?? '-' }}</p>
                        <p>{{ $comprobante->empresa->direccion_fiscal ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Cliente</strong>
                        <p class="mb-1">{{ $comprobante->cliente->nombre ?? 'Publico general' }}</p>
                        <p class="mb-1">{{ $comprobante->cliente->tipo_documento ?? '-' }}: {{ $comprobante->cliente->documento ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Datos SUNAT</strong>
                        <p class="mb-1">Fecha: {{ $comprobante->fecha_emision?->format('d/m/Y') }}</p>
                        <p class="mb-1">Moneda: {{ $comprobante->moneda_codigo }}</p>
                        <p class="mb-1">Hash: {{ $comprobante->xml_hash }}</p>
                    </div>
                </div>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Descripcion</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>IGV</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($comprobante->items as $item)
                            <tr>
                                <td>{{ $item->descripcion }}</td>
                                <td>{{ number_format((float) $item->cantidad, 2) }}</td>
                                <td>S/ {{ number_format((float) $item->precio_unitario, 2) }}</td>
                                <td>S/ {{ number_format((float) $item->igv, 2) }}</td>
                                <td>S/ {{ number_format((float) $item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>QR payload</strong>
                        <pre class="bg-light p-3 mb-0">{{ $comprobante->qr_payload }}</pre>
                    </div>
                    <div class="col-md-6">
                        <table class="table">
                            <tr><th>Subtotal</th><td>S/ {{ number_format((float) $comprobante->subtotal, 2) }}</td></tr>
                            <tr><th>IGV</th><td>S/ {{ number_format((float) $comprobante->igv_total, 2) }}</td></tr>
                            <tr><th>Total</th><td><strong>S/ {{ number_format((float) $comprobante->total, 2) }}</strong></td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
