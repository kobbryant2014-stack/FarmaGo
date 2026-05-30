@extends('layouts.admin')

@section('title', 'Detalle de proveedor - FarmaGo')

@section('content')
<section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h1>{{ $proveedor->nombre }}</h1>
        <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Volver</a>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">RUC</dt><dd class="col-sm-9">{{ $proveedor->ruc }}</dd>
                    <dt class="col-sm-3">Contacto</dt><dd class="col-sm-9">{{ $proveedor->contacto ?? '-' }}</dd>
                    <dt class="col-sm-3">Telefono</dt><dd class="col-sm-9">{{ $proveedor->telefono ?? '-' }}</dd>
                    <dt class="col-sm-3">Email</dt><dd class="col-sm-9">{{ $proveedor->email ?? '-' }}</dd>
                    <dt class="col-sm-3">Compras</dt><dd class="col-sm-9">{{ $proveedor->compras->count() }}</dd>
                </dl>
            </div>
        </div>
    </div>
</section>
@endsection
