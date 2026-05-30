@extends('layouts.admin')

@section('title', 'Editar lote - FarmaGo')

@section('content')
<section class="content-header"><div class="container-fluid"><h1>Editar lote</h1></div></section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <form method="POST" action="{{ route('lotes.update', $lote) }}">
                @method('PUT')
                @include('lotes._form')
            </form>
        </div>
    </div>
</section>
@endsection
