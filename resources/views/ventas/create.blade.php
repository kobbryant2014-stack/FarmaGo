@extends('layouts.admin')

@section('title', 'Nueva venta - FarmaGo')

@section('content')
<section class="content-header"><div class="container-fluid"><h1>Nueva venta</h1></div></section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <form method="POST" action="{{ route('ventas.store') }}">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Cliente</label>
                            <select class="form-control" name="cliente_id">
                                <option value="">Publico general</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre_completo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Metodo de pago</label>
                            <select class="form-control" name="metodo_pago" required>
                                @foreach(['efectivo' => 'Efectivo', 'tarjeta' => 'Tarjeta', 'yape' => 'Yape', 'plin' => 'Plin'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Comprobante</label>
                            <div class="btn-group btn-group-toggle d-flex comprobante-toggle" data-toggle="buttons">
                                <label class="btn btn-outline-info active">
                                    <input type="radio" name="tipo_comprobante" value="TICKET" checked> Ticket
                                </label>
                                <label class="btn btn-outline-success">
                                    <input type="radio" name="tipo_comprobante" value="BOLETA"> Boleta electronica
                                </label>
                                <label class="btn btn-outline-primary">
                                    <input type="radio" name="tipo_comprobante" value="FACTURA"> Factura electronica
                                </label>
                            </div>
                            <small class="form-text text-muted">Factura requiere cliente identificado.</small>
                        </div>
                    </div>

                    <div class="callout callout-info">
                        <div class="row align-items-end">
                            <div class="col-md-5 form-group mb-md-0">
                                <label><i class="fas fa-barcode"></i> Lectora de codigo de barras</label>
                                <input class="form-control form-control-lg" id="barcode-input" placeholder="Escanee o escriba codigo/nombre">
                            </div>
                            <div class="col-md-2 form-group mb-md-0">
                                <button type="button" class="btn btn-info btn-block btn-lg" id="barcode-search">
                                    Consultar
                                </button>
                            </div>
                            <div class="col-md-5">
                                <div id="barcode-result" class="price-reader-result">
                                    <span class="text-muted">El precio aparecera aqui al escanear.</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5>Detalle</h5>
                    <div class="table-responsive">
                        <table class="table" id="detalle-venta">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Descuento</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select class="form-control producto-select" name="productos[0][producto_id]" required>
                                            @foreach($productos as $producto)
                                                <option value="{{ $producto->id }}" data-precio="{{ $producto->precio_venta }}">
                                                    {{ $producto->nombre }} - Stock {{ number_format($producto->stock_disponible, 2) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input class="form-control" type="number" min="1" step="1" name="productos[0][cantidad]" required></td>
                                    <td><input class="form-control precio-input" type="number" min="0" step="0.01" name="productos[0][precio_unitario]"></td>
                                    <td><input class="form-control" type="number" min="0" step="0.01" name="productos[0][descuento]" value="0"></td>
                                    <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-outline-primary" id="add-venta-row">Agregar producto</button>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button class="btn btn-primary"><i class="fas fa-save"></i> Registrar venta</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
let ventaIndex = 1;
function syncPrecio(row) {
    const select = row.querySelector('.producto-select');
    const precio = select.options[select.selectedIndex].dataset.precio || '';
    row.querySelector('.precio-input').value = precio;
}
document.querySelectorAll('#detalle-venta tbody tr').forEach(syncPrecio);
document.addEventListener('change', function (event) {
    if (event.target.classList.contains('producto-select')) syncPrecio(event.target.closest('tr'));
});
document.getElementById('add-venta-row').addEventListener('click', function () {
    const tbody = document.querySelector('#detalle-venta tbody');
    const first = tbody.querySelector('tr');
    const clone = first.cloneNode(true);
    clone.querySelectorAll('input, select').forEach(function (input) {
        input.name = input.name.replace(/productos\[\d+\]/, 'productos[' + ventaIndex + ']');
        if (input.tagName === 'INPUT') input.value = input.name.includes('[descuento]') ? '0' : '';
    });
    ventaIndex++;
    tbody.appendChild(clone);
    syncPrecio(clone);
});
document.addEventListener('click', function (event) {
    if (event.target.closest('.remove-row') && document.querySelectorAll('#detalle-venta tbody tr').length > 1) {
        event.target.closest('tr').remove();
    }
});

function renderBarcodeResult(data) {
    const result = document.getElementById('barcode-result');
    if (!data.ok) {
        result.innerHTML = '<span class="text-danger font-weight-bold">' + data.message + '</span>';
        return;
    }

    const producto = data.producto;
    result.innerHTML =
        '<div class="d-flex justify-content-between align-items-center">' +
            '<div>' +
                '<strong>' + producto.nombre + '</strong><br>' +
                '<small>' + producto.codigo + ' | ' + producto.laboratorio + '</small><br>' +
                '<span class="' + (producto.alerta_stock ? 'text-danger' : 'text-success') + '">Stock: ' + producto.stock_disponible + '</span>' +
            '</div>' +
            '<div class="text-right">' +
                '<span class="price-reader-amount">S/ ' + producto.precio_venta + '</span><br>' +
                '<button type="button" class="btn btn-sm btn-success mt-2" id="add-barcode-product" data-product-id="' + producto.id + '" data-price="' + producto.precio_venta + '">Agregar</button>' +
            '</div>' +
        '</div>';
}

function consultBarcode() {
    const input = document.getElementById('barcode-input');
    const codigo = input.value.trim();
    if (!codigo) return;

    fetch('{{ route('productos.buscar-por-codigo') }}?codigo=' + encodeURIComponent(codigo), {
        headers: { 'Accept': 'application/json' }
    })
        .then(async response => {
            const data = await response.json();
            renderBarcodeResult(data);
        })
        .catch(() => renderBarcodeResult({ ok: false, message: 'No se pudo consultar el producto.' }));
}

document.getElementById('barcode-search').addEventListener('click', consultBarcode);
document.getElementById('barcode-input').addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        consultBarcode();
    }
});
document.addEventListener('click', function (event) {
    const button = event.target.closest('#add-barcode-product');
    if (!button) return;

    let row = Array.from(document.querySelectorAll('#detalle-venta tbody tr')).find(function (tr) {
        return !tr.querySelector('.producto-select').value || tr.querySelector('input[name$="[cantidad]"]').value === '';
    });

    if (!row) {
        document.getElementById('add-venta-row').click();
        row = document.querySelector('#detalle-venta tbody tr:last-child');
    }

    row.querySelector('.producto-select').value = button.dataset.productId;
    row.querySelector('.precio-input').value = button.dataset.price;
    row.querySelector('input[name$="[cantidad]"]').value = row.querySelector('input[name$="[cantidad]"]').value || '1';
    document.getElementById('barcode-input').value = '';
    document.getElementById('barcode-input').focus();
});
</script>
@endpush
