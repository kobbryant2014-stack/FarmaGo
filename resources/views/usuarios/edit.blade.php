@extends('layouts.admin')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <h1><i class="fas fa-user-edit"></i> Editar usuario</h1>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                <h3 class="card-title">Actualizar informacion</h3>
            </div>

            <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input class="form-control" name="name" value="{{ old('name', $usuario->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input class="form-control" type="email" name="email" value="{{ old('email', $usuario->email) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Nueva contrasena</label>
                        <input class="form-control" type="password" name="password" minlength="8" placeholder="Dejar vacio para no cambiar">
                    </div>

                    <div class="form-group">
                        <label>Rol</label>
                        <select class="form-control" name="role" required>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" @selected($usuario->hasRole($role))>{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="active" value="0">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="active" name="active" value="1" @checked(old('active', $usuario->active))>
                        <label class="custom-control-label" for="active">Usuario activo</label>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button class="btn btn-primary"><i class="fas fa-sync"></i> Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
