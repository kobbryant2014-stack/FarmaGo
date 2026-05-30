@extends('layouts.admin')

@section('content')

{{-- HEADER --}}
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-users"></i> Usuarios</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Nuevo Usuario
                </a>
            </div>
        </div>
    </div>
</section>

{{-- CONTENIDO --}}
<section class="content">
    <div class="container-fluid">

        <div class="card shadow-sm">
            <div class="card-header bg-dark">
                <h3 class="card-title">Listado de usuarios</h3>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th class="text-center" width="220">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-info">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @if(! $user->active)
                                        <span class="badge badge-danger">Inactivo</span>
                                    @elseif($user->locked_until && $user->locked_until->isFuture())
                                        <span class="badge badge-warning">Bloqueado</span>
                                    @else
                                        <span class="badge badge-success">Activo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('usuarios.edit', $user) }}"
                                       class="btn btn-sm btn-warning"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('usuarios.toggle-lock', $user) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-secondary"
                                                title="Bloquear o desbloquear">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('usuarios.destroy', $user) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                                title="Desactivar"
                                                onclick="return confirm('¿Desactivar usuario?')">
                                            <i class="fas fa-user-slash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No hay usuarios registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</section>

<div class="px-3">
    {{ $users->links() }}
</div>

@endsection
