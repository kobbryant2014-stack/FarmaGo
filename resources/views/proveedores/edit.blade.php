@extends('layouts.admin')

@section('title', 'Editar proveedor - FarmaGo')

@section('content')
<section class="content-header"><div class="container-fluid"><h1>Editar proveedor</h1></div></section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <form method="POST" action="{{ route('proveedores.update', $proveedor) }}">
                @method('PUT')
                @include('proveedores._form')
            </form>
        </div>
    </div>
</section>
@endsection
