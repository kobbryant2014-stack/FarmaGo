@extends('layouts.admin')

@section('title', 'Nuevo producto - FarmaGo')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <h1><i class="fas fa-plus"></i> Nuevo producto</h1>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <form method="POST" action="{{ route('productos.store') }}">
                @include('productos._form')
            </form>
        </div>
    </div>
</section>
@endsection
