@csrf

<div class="card-body">
    <div class="row">
        <div class="col-md-4 form-group">
            <label>Codigo de barra</label>
            <input class="form-control" name="codigo_barra" value="{{ old('codigo_barra', $producto->codigo_barra) }}">
        </div>
        <div class="col-md-4 form-group">
            <label>Codigo interno</label>
            <input class="form-control" name="codigo_interno" value="{{ old('codigo_interno', $producto->codigo_interno) }}">
        </div>
        <div class="col-md-4 form-group">
            <label>Categoria</label>
            <select class="form-control" name="categoria_id" required>
                <option value="">Seleccione</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" @selected(old('categoria_id', $producto->categoria_id) == $categoria->id)>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-8 form-group">
            <label>Nombre</label>
            <input class="form-control" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
        </div>
        <div class="col-md-4 form-group">
            <label>Registro sanitario</label>
            <input class="form-control" name="registro_sanitario" value="{{ old('registro_sanitario', $producto->registro_sanitario) }}">
        </div>
        <div class="col-md-4 form-group">
            <label>DCI</label>
            <input class="form-control" name="dci" value="{{ old('dci', $producto->dci) }}">
        </div>
        <div class="col-md-4 form-group">
            <label>Principio activo</label>
            <input class="form-control" name="principio_activo_texto" value="{{ old('principio_activo_texto', $producto->principio_activo_texto) }}">
        </div>
        <div class="col-md-4 form-group">
            <label>Estado</label>
            <select class="form-control" name="estado">
                @foreach(['activo' => 'Activo', 'inactivo' => 'Inactivo', 'inmovilizado' => 'Inmovilizado'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('estado', $producto->estado ?? 'activo') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 form-group">
            <label>Precio compra</label>
            <input class="form-control" type="number" step="0.01" min="0" name="precio_compra" value="{{ old('precio_compra', $producto->precio_compra ?? 0) }}">
        </div>
        <div class="col-md-3 form-group">
            <label>Precio venta</label>
            <input class="form-control" type="number" step="0.01" min="0" name="precio_venta" value="{{ old('precio_venta', $producto->precio_venta) }}" required>
        </div>
        <div class="col-md-3 form-group">
            <label>Stock minimo</label>
            <input class="form-control" type="number" min="0" name="stock_minimo" value="{{ old('stock_minimo', $producto->stock_minimo ?? 0) }}" required>
        </div>
        <div class="col-md-3 form-group">
            <label>Stock maximo</label>
            <input class="form-control" type="number" min="0" name="stock_maximo" value="{{ old('stock_maximo', $producto->stock_maximo) }}">
        </div>
        <div class="col-md-12 form-group">
            <label>Descripcion</label>
            <textarea class="form-control" name="descripcion" rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
        </div>
        <div class="col-md-6">
            <input type="hidden" name="requiere_receta" value="0">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="requiere_receta" name="requiere_receta" value="1" @checked(old('requiere_receta', $producto->requiere_receta))>
                <label class="custom-control-label" for="requiere_receta">Requiere receta</label>
            </div>
        </div>
        <div class="col-md-6">
            <input type="hidden" name="activo" value="0">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="activo" name="activo" value="1" @checked(old('activo', $producto->activo ?? true))>
                <label class="custom-control-label" for="activo">Producto activo</label>
            </div>
        </div>
    </div>
</div>

<div class="card-footer text-right">
    <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
    <button class="btn btn-primary">
        <i class="fas fa-save"></i> Guardar
    </button>
</div>
