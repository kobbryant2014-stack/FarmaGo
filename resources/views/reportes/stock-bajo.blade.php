@extends('layouts.admin')

@section('title', 'Reporte stock bajo - FarmaGo')

@section('content')
<section class="content-header"><div class="container-fluid"><h1>Productos con stock bajo o al limite</h1></div></section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table">
                    <thead><tr><th>Producto</th><th>Categoria</th><th>Stock disponible</th><th>Stock minimo</th></tr></thead>
                    <tbody>
                        @forelse($productos as $producto)
                            <tr>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $producto->categoria->nombre ?? '-' }}</td>
                                <td>{{ number_format($producto->stock_disponible, 2) }}</td>
                                <td>{{ $producto->stock_minimo }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">Sin productos con stock bajo o al limite.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
