@csrf

<div class="card-body">
    <div class="row">
        <div class="col-md-4 form-group">
            <label>RUC</label>
            <input class="form-control" name="ruc" value="{{ old('ruc', $proveedor->ruc) }}" required>
        </div>
        <div class="col-md-8 form-group">
            <label>Razon social / nombre</label>
            <input class="form-control" name="nombre" value="{{ old('nombre', $proveedor->nombre) }}" required>
        </div>
        <div class="col-md-8 form-group">
            <label>Razon social SUNAT</label>
            <input class="form-control" name="razon_social" value="{{ old('razon_social', $proveedor->razon_social) }}">
        </div>
        <div class="col-md-4 form-group">
            <label>Contacto</label>
            <input class="form-control" name="contacto" value="{{ old('contacto', $proveedor->contacto) }}">
        </div>
        <div class="col-md-4 form-group">
            <label>Telefono</label>
            <input class="form-control" name="telefono" value="{{ old('telefono', $proveedor->telefono) }}">
        </div>
        <div class="col-md-4 form-group">
            <label>Email</label>
            <input class="form-control" type="email" name="email" value="{{ old('email', $proveedor->email) }}">
        </div>
        <div class="col-md-4 form-group">
            <label>Direccion</label>
            <input class="form-control" name="direccion" value="{{ old('direccion', $proveedor->direccion) }}">
        </div>
        <div class="col-md-12">
            <input type="hidden" name="activo" value="0">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="activo" name="activo" value="1" @checked(old('activo', $proveedor->activo ?? true))>
                <label class="custom-control-label" for="activo">Proveedor activo</label>
            </div>
        </div>
    </div>
</div>

<div class="card-footer text-right">
    <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
    <button class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
</div>
