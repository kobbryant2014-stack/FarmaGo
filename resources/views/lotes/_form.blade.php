@csrf

<div class="card-body">
    <div class="row">
        <div class="col-md-6 form-group">
            <label>Producto</label>
            <select class="form-control" name="producto_id" required>
                <option value="">Seleccione</option>
                @foreach($productos as $producto)
                    <option value="{{ $producto->id }}" @selected(old('producto_id', $lote->producto_id) == $producto->id)>{{ $producto->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 form-group">
            <label>Compra asociada</label>
            <select class="form-control" name="compra_id" required>
                <option value="">Seleccione</option>
                @foreach($compras as $compra)
                    <option value="{{ $compra->id }}" @selected(old('compra_id', $lote->compra_id) == $compra->id)>Compra #{{ $compra->id }} - {{ $compra->fecha?->format('d/m/Y') }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 form-group">
            <label>Proveedor</label>
            <select class="form-control" name="proveedor_id" required>
                <option value="">Seleccione</option>
                @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}" @selected(old('proveedor_id', $lote->proveedor_id) == $proveedor->id)>{{ $proveedor->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 form-group">
            <label>Almacen</label>
            <select class="form-control" name="almacen_id">
                <option value="">Sin almacen</option>
                @foreach($almacenes as $almacen)
                    <option value="{{ $almacen->id }}" @selected(old('almacen_id', $lote->almacen_id) == $almacen->id)>{{ $almacen->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 form-group">
            <label>Numero de lote</label>
            <input class="form-control" name="numero_lote" value="{{ old('numero_lote', $lote->numero_lote) }}" required>
        </div>
        <div class="col-md-3 form-group">
            <label>Fabricacion</label>
            <input class="form-control" type="date" name="fecha_fabricacion" value="{{ old('fecha_fabricacion', optional($lote->fecha_fabricacion)->format('Y-m-d')) }}">
        </div>
        <div class="col-md-3 form-group">
            <label>Vencimiento</label>
            <input class="form-control" type="date" name="fecha_vencimiento" value="{{ old('fecha_vencimiento', optional($lote->fecha_vencimiento)->format('Y-m-d')) }}" required>
        </div>
        <div class="col-md-3 form-group">
            <label>Cantidad inicial</label>
            <input class="form-control" type="number" min="0" step="1" name="stock_inicial" value="{{ old('stock_inicial', $lote->stock_inicial ?? 0) }}" required>
        </div>
        <div class="col-md-3 form-group">
            <label>Precio compra</label>
            <input class="form-control" type="number" min="0" step="0.01" name="precio_compra" value="{{ old('precio_compra', $lote->precio_compra ?? 0) }}" required>
        </div>
        <div class="col-md-4 form-group">
            <label>Estado</label>
            <select class="form-control" name="estado">
                @foreach(['activo', 'inmovilizado', 'retirado', 'vencido', 'agotado'] as $estado)
                    <option value="{{ $estado }}" @selected(old('estado', $lote->estado ?? 'activo') === $estado)>{{ ucfirst($estado) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-8 form-group">
            <label>Motivo de bloqueo</label>
            <input class="form-control" name="motivo_bloqueo" value="{{ old('motivo_bloqueo', $lote->motivo_bloqueo) }}">
        </div>
        <div class="col-md-12">
            <input type="hidden" name="activo" value="0">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="activo" name="activo" value="1" @checked(old('activo', $lote->activo ?? true))>
                <label class="custom-control-label" for="activo">Lote activo</label>
            </div>
        </div>
    </div>
</div>

<div class="card-footer text-right">
    <a href="{{ route('lotes.index') }}" class="btn btn-secondary">Cancelar</a>
    <button class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
</div>
