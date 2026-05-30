@csrf

<div class="card-body">
    <div class="row">
        <div class="col-md-3 form-group">
            <label>Tipo documento</label>
            <select class="form-control" name="tipo_documento">
                @foreach(['DNI' => 'DNI', 'RUC' => 'RUC', 'OTRO' => 'Otro'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('tipo_documento', $cliente->tipo_documento) === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 form-group">
            <label>Documento</label>
            <input class="form-control" name="documento" value="{{ old('documento', $cliente->documento) }}">
        </div>
        <div class="col-md-6 form-group">
            <label>Nombre o razon social</label>
            <input class="form-control" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" required>
        </div>
        <div class="col-md-6 form-group">
            <label>Nombres</label>
            <input class="form-control" name="nombres" value="{{ old('nombres', $cliente->nombres) }}">
        </div>
        <div class="col-md-6 form-group">
            <label>Apellidos</label>
            <input class="form-control" name="apellidos" value="{{ old('apellidos', $cliente->apellidos) }}">
        </div>
        <div class="col-md-4 form-group">
            <label>Telefono</label>
            <input class="form-control" name="telefono" value="{{ old('telefono', $cliente->telefono) }}">
        </div>
        <div class="col-md-4 form-group">
            <label>Email</label>
            <input class="form-control" type="email" name="email" value="{{ old('email', $cliente->email) }}">
        </div>
        <div class="col-md-4 form-group">
            <label>Razon social</label>
            <input class="form-control" name="razon_social" value="{{ old('razon_social', $cliente->razon_social) }}">
        </div>
        <div class="col-md-12 form-group">
            <label>Direccion</label>
            <input class="form-control" name="direccion" value="{{ old('direccion', $cliente->direccion) }}">
        </div>
        <div class="col-md-12">
            <input type="hidden" name="activo" value="0">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="activo" name="activo" value="1" @checked(old('activo', $cliente->activo ?? true))>
                <label class="custom-control-label" for="activo">Cliente activo</label>
            </div>
        </div>
    </div>
</div>

<div class="card-footer text-right">
    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
    <button class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
</div>
