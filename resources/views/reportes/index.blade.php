@extends('layouts.admin')

@section('title', 'Reportes - FarmaGo')

@section('content')
<section class="content-header"><div class="container-fluid"><h1><i class="fas fa-chart-line"></i> Reportes</h1></div></section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            @foreach([
                ['route' => 'reportes.ventas', 'icon' => 'fa-receipt', 'title' => 'Ventas por fecha'],
                ['route' => 'reportes.stock-bajo', 'icon' => 'fa-exclamation-triangle', 'title' => 'Stock bajo'],
                ['route' => 'reportes.vencimientos', 'icon' => 'fa-calendar-times', 'title' => 'Vencimientos'],
                ['route' => 'reportes.productos-mas-vendidos', 'icon' => 'fa-star', 'title' => 'Productos mas vendidos'],
            ] as $reporte)
                <div class="col-md-3">
                    <a class="small-box bg-info" href="{{ route($reporte['route']) }}">
                        <div class="inner"><h4>{{ $reporte['title'] }}</h4><p>Consultar</p></div>
                        <div class="icon"><i class="fas {{ $reporte['icon'] }}"></i></div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
