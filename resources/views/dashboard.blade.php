@extends('layouts.admin')

@section('title', 'Dashboard - FarmaGo')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="dashboard-hero">
            <div>
                <span class="dashboard-kicker">Panel principal</span>
                <h1>Dashboard FarmaGo</h1>
                <p>Resumen de ventas, inventario y vencimientos para tomar decisiones rapido.</p>
            </div>
            <div class="dashboard-hero-icon">
                <i class="fas fa-clinic-medical"></i>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box stat-box stat-box-sky">
                    <div class="inner"><h3>{{ $total_productos }}</h3><p>Productos</p></div>
                    <div class="icon"><i class="fas fa-pills"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box stat-box stat-box-green">
                    <div class="inner"><h3>{{ $total_clientes }}</h3><p>Clientes</p></div>
                    <div class="icon"><i class="fas fa-users"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box stat-box stat-box-cyan">
                    <div class="inner"><h3>{{ $ventas_dia }}</h3><p>Ventas de hoy</p></div>
                    <div class="icon"><i class="fas fa-cash-register"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box stat-box stat-box-blue">
                    <div class="inner"><h3>S/ {{ number_format((float) $total_vendido_hoy, 2) }}</h3><p>Total vendido hoy</p></div>
                    <div class="icon"><i class="fas fa-chart-line"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Stock bajo o al limite</h3></div>
                    <div class="card-body table-responsive p-0">
                        <table class="table">
                            <thead><tr><th>Producto</th><th>Disponible</th><th>Minimo</th></tr></thead>
                            <tbody>
                                @forelse($productos_stock_bajo->take(5) as $producto)
                                    <tr>
                                        <td>{{ $producto->nombre }}</td>
                                        <td>{{ number_format($producto->stock_disponible, 2) }}</td>
                                        <td>{{ $producto->stock_minimo }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted">Sin alertas de stock bajo o al limite.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"><a href="{{ route('reportes.stock-bajo') }}">Ver reporte completo</a></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Proximos vencimientos</h3></div>
                    <div class="card-body table-responsive p-0">
                        <table class="table">
                            <thead><tr><th>Lote</th><th>Producto</th><th>Vence</th></tr></thead>
                            <tbody>
                                @forelse($lotes_proximos_vencer->take(5) as $lote)
                                    <tr>
                                        <td>{{ $lote->numero_lote }}</td>
                                        <td>{{ $lote->producto->nombre ?? '-' }}</td>
                                        <td>{{ $lote->fecha_vencimiento?->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted">Sin vencimientos cercanos.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"><a href="{{ route('reportes.vencimientos') }}">Ver reporte completo</a></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3 class="card-title">Ultimas ventas</h3></div>
            <div class="card-body table-responsive p-0">
                <table class="table">
                    <thead><tr><th>#</th><th>Cliente</th><th>Fecha</th><th>Total</th></tr></thead>
                    <tbody>
                        @forelse($ultimas_ventas as $venta)
                            <tr>
                                <td>{{ $venta->id }}</td>
                                <td>{{ $venta->cliente->nombre ?? 'Publico general' }}</td>
                                <td>{{ $venta->fecha?->format('d/m/Y H:i') }}</td>
                                <td>S/ {{ number_format((float) $venta->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">Sin ventas registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
