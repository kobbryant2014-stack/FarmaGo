@extends('layouts.admin')

@section('title', 'Nuevo lote - FarmaGo')

@section('content')
<section class="content-header"><div class="container-fluid"><h1>Nuevo lote</h1></div></section>
<section class="content">
    <div class="container-fluid">
        @if($compras->isEmpty())
            <div class="alert alert-warning">Debe registrar una compra antes de crear lotes manuales.</div>
        @endif
        <div class="card">
            <form method="POST" action="{{ route('lotes.store') }}">
                @include('lotes._form')
            </form>
        </div>
    </div>
</section>
@endsection
