@extends('layouts.admin')

@section('title', 'Detalle de cliente - FarmaGo')

@section('content')
<section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h1>{{ $cliente->nombre }}</h1>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Volver</a>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Documento</dt><dd class="col-sm-9">{{ $cliente->tipo_documento }} {{ $cliente->documento }}</dd>
                    <dt class="col-sm-3">Telefono</dt><dd class="col-sm-9">{{ $cliente->telefono ?? '-' }}</dd>
                    <dt class="col-sm-3">Email</dt><dd class="col-sm-9">{{ $cliente->email ?? '-' }}</dd>
                    <dt class="col-sm-3">Direccion</dt><dd class="col-sm-9">{{ $cliente->direccion ?? '-' }}</dd>
                    <dt class="col-sm-3">Ventas</dt><dd class="col-sm-9">{{ $cliente->ventas->count() }}</dd>
                </dl>
            </div>
        </div>
    </div>
</section>
@endsection
