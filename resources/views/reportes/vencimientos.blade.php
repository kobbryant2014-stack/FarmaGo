@extends('layouts.admin')

@section('title', 'Reporte vencimientos - FarmaGo')

@section('content')
<section class="content-header"><div class="container-fluid"><h1>Lotes proximos a vencer</h1></div></section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table">
                    <thead><tr><th>Lote</th><th>Producto</th><th>Vencimiento</th><th>Dias</th><th>Stock</th></tr></thead>
                    <tbody>
                        @forelse($lotes as $lote)
                            <tr>
                                <td>{{ $lote->numero_lote }}</td>
                                <td>{{ $lote->producto->nombre ?? '-' }}</td>
                                <td>{{ $lote->fecha_vencimiento?->format('d/m/Y') }}</td>
                                <td>{{ $lote->dias_para_vencer }}</td>
                                <td>{{ number_format($lote->stock_actual, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">Sin lotes proximos a vencer.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
