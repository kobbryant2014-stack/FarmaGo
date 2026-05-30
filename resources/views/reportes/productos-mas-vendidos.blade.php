@extends('layouts.admin')

@section('title', 'Productos mas vendidos - FarmaGo')

@section('content')
<section class="content-header"><div class="container-fluid"><h1>Productos mas vendidos</h1></div></section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table">
                    <thead><tr><th>Producto</th><th>Cantidad vendida</th><th>Total vendido</th></tr></thead>
                    <tbody>
                        @forelse($productos as $item)
                            <tr>
                                <td>{{ $item->producto->nombre ?? '-' }}</td>
                                <td>{{ number_format($item->cantidad_total, 2) }}</td>
                                <td>S/ {{ number_format((float) $item->total_vendido, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">Sin ventas registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
