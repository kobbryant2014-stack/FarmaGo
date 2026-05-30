@extends('layouts.admin')

@section('title', 'Kardex - FarmaGo')

@section('content')
<section class="content-header"><div class="container-fluid"><h1><i class="fas fa-clipboard-list"></i> Kardex</h1></div></section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Kardex por producto</h3></div>
                    <div class="card-body">
                        <form method="GET" id="producto-form">
                            <select class="form-control mb-3" id="producto-select">
                                @foreach($productos as $producto)
                                    <option value="{{ route('kardex.producto', $producto) }}">{{ $producto->nombre }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary" onclick="window.location=document.getElementById('producto-select').value">Consultar</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Kardex por lote</h3></div>
                    <div class="card-body">
                        <select class="form-control mb-3" id="lote-select">
                            @foreach($lotes as $lote)
                                <option value="{{ route('kardex.lote', $lote) }}">{{ $lote->numero_lote }} - {{ $lote->producto->nombre ?? '-' }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-primary" onclick="window.location=document.getElementById('lote-select').value">Consultar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
