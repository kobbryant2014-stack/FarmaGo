@extends('layouts.admin')

@section('title', 'Nueva compra - FarmaGo')

@section('content')
<section class="content-header"><div class="container-fluid"><h1>Nueva compra</h1></div></section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <form method="POST" action="{{ route('compras.store') }}">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Proveedor</label>
                            <select class="form-control" name="proveedor_id" required>
                                <option value="">Seleccione</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Almacen</label>
                            <select class="form-control" name="almacen_id">
                                <option value="">Sin almacen</option>
                                @foreach($almacenes as $almacen)
                                    <option value="{{ $almacen->id }}">{{ $almacen->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Fecha</label>
                            <input class="form-control" type="datetime-local" name="fecha">
                        </div>
                    </div>

                    <h5>Detalle</h5>
                    <div class="table-responsive">
                        <table class="table" id="detalle-compra">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Lote</th>
                                    <th>Vencimiento</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select class="form-control" name="productos[0][producto_id]" required>
                                            @foreach($productos as $producto)
                                                <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input class="form-control" name="productos[0][numero_lote]" required></td>
                                    <td><input class="form-control" type="date" name="productos[0][fecha_vencimiento]" required></td>
                                    <td><input class="form-control" type="number" min="1" step="1" name="productos[0][cantidad]" required></td>
                                    <td><input class="form-control" type="number" min="0" step="0.01" name="productos[0][precio_unitario]" required></td>
                                    <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-outline-primary" id="add-compra-row">Agregar producto</button>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('compras.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button class="btn btn-primary"><i class="fas fa-save"></i> Registrar compra</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
let compraIndex = 1;
document.getElementById('add-compra-row').addEventListener('click', function () {
    const tbody = document.querySelector('#detalle-compra tbody');
    const first = tbody.querySelector('tr');
    const clone = first.cloneNode(true);
    clone.querySelectorAll('input, select').forEach(function (input) {
        input.name = input.name.replace(/productos\[\d+\]/, 'productos[' + compraIndex + ']');
        if (input.tagName === 'INPUT') input.value = '';
    });
    compraIndex++;
    tbody.appendChild(clone);
});
document.addEventListener('click', function (event) {
    if (event.target.closest('.remove-row') && document.querySelectorAll('#detalle-compra tbody tr').length > 1) {
        event.target.closest('tr').remove();
    }
});
</script>
@endpush
