@extends('layouts.admin')

@section('title', 'Editar cliente - FarmaGo')

@section('content')
<section class="content-header"><div class="container-fluid"><h1>Editar cliente</h1></div></section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <form method="POST" action="{{ route('clientes.update', $cliente) }}">
                @method('PUT')
                @include('clientes._form')
            </form>
        </div>
    </div>
</section>
@endsection
