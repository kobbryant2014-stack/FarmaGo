@extends('layouts.admin')

@section('title', 'Nuevo proveedor - FarmaGo')

@section('content')
<section class="content-header"><div class="container-fluid"><h1>Nuevo proveedor</h1></div></section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <form method="POST" action="{{ route('proveedores.store') }}">
                @include('proveedores._form')
            </form>
        </div>
    </div>
</section>
@endsection
