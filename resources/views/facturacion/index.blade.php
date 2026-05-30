@extends('layouts.admin')

@section('title', 'Facturacion electronica - FarmaGo')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <h1>Facturacion electronica SUNAT</h1>
        <p class="text-muted mb-0">Comprobantes electronicos registrados para envio a SUNAT/PSE/OSE.</p>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="alert alert-info">
            <strong>Modo local:</strong> el sistema genera serie, correlativo, QR y detalle SUNAT. Para envio real faltan RUC valido, certificado digital y credenciales SOL/PSE/OSE.
        </div>

        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Comprobante</th>
                            <th>Tipo</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado SUNAT</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comprobantes as $comprobante)
                            <tr>
                                <td>{{ $comprobante->numero_completo }}</td>
                                <td>{{ $comprobante->tipo_comprobante === '01' ? 'Factura' : 'Boleta' }}</td>
                                <td>{{ $comprobante->cliente->nombre ?? 'Publico general' }}</td>
                                <td>{{ $comprobante->fecha_emision?->format('d/m/Y') }}</td>
                                <td>S/ {{ number_format((float) $comprobante->total, 2) }}</td>
                                <td><span class="badge badge-info">{{ $comprobante->estado_sunat ?? $comprobante->estado }}</span></td>
                                <td class="text-right">
                                    <a class="btn btn-sm btn-primary" href="{{ route('facturacion.show', $comprobante) }}">Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted">Aun no hay comprobantes electronicos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $comprobantes->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
