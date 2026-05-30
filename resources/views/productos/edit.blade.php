@extends('layouts.admin')

@section('title', 'Editar producto - FarmaGo')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <h1><i class="fas fa-edit"></i> Editar producto</h1>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <form method="POST" action="{{ route('productos.update', $producto) }}">
                @method('PUT')
                @include('productos._form')
            </form>
        </div>
    </div>
</section>
@endsection
