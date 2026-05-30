@extends('layouts.admin')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <h1><i class="fas fa-user-plus"></i> Nuevo usuario</h1>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-primary">
                <h3 class="card-title">Datos del usuario</h3>
            </div>

            <form method="POST" action="{{ route('usuarios.store') }}">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input class="form-control" type="email" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Contrasena</label>
                        <input class="form-control" type="password" name="password" minlength="8" required>
                    </div>

                    <div class="form-group">
                        <label>Rol</label>
                        <select class="form-control" name="role" required>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" @selected(old('role') === $role)>{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="active" value="0">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="active" name="active" value="1" checked>
                        <label class="custom-control-label" for="active">Usuario activo</label>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
