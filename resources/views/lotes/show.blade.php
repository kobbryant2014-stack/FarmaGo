@extends('layouts.admin')

@section('title', 'Detalle de lote - FarmaGo')

@section('content')
<section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h1>Lote {{ $lote->numero_lote }}</h1>
        <a href="{{ route('lotes.index') }}" class="btn btn-secondary">Volver</a>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Producto</dt><dd class="col-sm-9">{{ $lote->producto->nombre ?? '-' }}</dd>
                    <dt class="col-sm-3">Proveedor</dt><dd class="col-sm-9">{{ $lote->proveedor->nombre ?? '-' }}</dd>
                    <dt class="col-sm-3">Almacen</dt><dd class="col-sm-9">{{ $lote->almacen->nombre ?? '-' }}</dd>
                    <dt class="col-sm-3">Vencimiento</dt><dd class="col-sm-9">{{ $lote->fecha_vencimiento?->format('d/m/Y') }}</dd>
                    <dt class="col-sm-3">Stock actual</dt><dd class="col-sm-9">{{ number_format($lote->stock_actual, 2) }}</dd>
                    <dt class="col-sm-3">Estado</dt><dd class="col-sm-9">{{ ucfirst($lote->estado ?? 'activo') }}</dd>
                </dl>
                <a class="btn btn-outline-primary" href="{{ route('kardex.lote', $lote) }}">Ver Kardex del lote</a>
            </div>
        </div>
    </div>
</section>
@endsection
