@extends('layouts.admin')

@section('title', 'Consultar precios - FarmaGo')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <h1><i class="fas fa-barcode"></i> Consultar precios</h1>
        <p class="text-muted mb-0">Use la lectora de codigo de barras o escriba codigo/nombre del producto.</p>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card price-reader-card">
            <div class="card-body">
                <label for="price-code" class="h5">Codigo de barras / codigo interno</label>
                <div class="input-group input-group-lg">
                    <input id="price-code" class="form-control" autocomplete="off" autofocus placeholder="Escanee aqui">
                    <div class="input-group-append">
                        <button id="price-search" class="btn btn-primary" type="button">
                            <i class="fas fa-search"></i> Consultar
                        </button>
                    </div>
                </div>
                <small class="form-text text-muted">La mayoria de lectoras presionan Enter automaticamente al terminar el escaneo.</small>

                <div id="price-result" class="price-reader-panel mt-4">
                    <i class="fas fa-barcode"></i>
                    <span>Esperando lectura...</span>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function showPriceResult(data) {
    const result = document.getElementById('price-result');

    if (!data.ok) {
        result.className = 'price-reader-panel price-reader-error mt-4';
        result.innerHTML = '<i class="fas fa-exclamation-circle"></i><span>' + data.message + '</span>';
        return;
    }

    const producto = data.producto;
    result.className = 'price-reader-panel price-reader-success mt-4';
    result.innerHTML =
        '<div>' +
            '<div class="price-reader-name">' + producto.nombre + '</div>' +
            '<div class="price-reader-meta">' + producto.codigo + ' | ' + producto.laboratorio + ' | ' + producto.presentacion + '</div>' +
            '<div class="' + (producto.alerta_stock ? 'text-danger' : 'text-success') + '">Stock disponible: ' + producto.stock_disponible + '</div>' +
        '</div>' +
        '<div class="price-reader-total">S/ ' + producto.precio_venta + '</div>';
}

function searchPrice() {
    const input = document.getElementById('price-code');
    const codigo = input.value.trim();
    if (!codigo) return;

    fetch('{{ route('productos.buscar-por-codigo') }}?codigo=' + encodeURIComponent(codigo), {
        headers: { 'Accept': 'application/json' }
    })
        .then(async response => {
            const data = await response.json();
            showPriceResult(data);
            input.select();
        })
        .catch(() => showPriceResult({ ok: false, message: 'No se pudo consultar el producto.' }));
}

document.getElementById('price-search').addEventListener('click', searchPrice);
document.getElementById('price-code').addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        searchPrice();
    }
});
</script>
@endpush
