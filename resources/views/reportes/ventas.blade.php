@extends('layouts.admin')

@section('title', 'Reporte de ventas - FarmaGo')

@section('content')
<section class="content-header"><div class="container-fluid"><h1>Reporte de ventas</h1></div></section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <form method="GET" class="form-inline">
                    <input class="form-control mr-2" type="date" name="desde" value="{{ request('desde') }}">
                    <input class="form-control mr-2" type="date" name="hasta" value="{{ request('hasta') }}">
                    <button class="btn btn-outline-primary">Filtrar</button>
                </form>
            </div>
            <div class="card-body">
                <h4>Total: S/ {{ number_format((float) $total, 2) }}</h4>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table">
                    <thead><tr><th>#</th><th>Fecha</th><th>Cliente</th><th>Total</th></tr></thead>
                    <tbody>
                        @forelse($ventas as $venta)
                            <tr>
                                <td>{{ $venta->id }}</td>
                                <td>{{ $venta->fecha?->format('d/m/Y H:i') }}</td>
                                <td>{{ $venta->cliente->nombre ?? 'Publico general' }}</td>
                                <td>S/ {{ number_format((float) $venta->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">Sin ventas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
