<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Throwable;

class UsuarioController extends Controller
{
    public function index(): View
    {
        return view('usuarios.index', [
            'users' => User::with('roles')->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('usuarios.create', [
            'roles' => $this->roles(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'exists:roles,name'],
            'active' => ['nullable', 'boolean'],
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'active' => $request->boolean('active', true),
                'email_verified_at' => now(),
            ]);

            $user->syncRoles([$validated['role']]);

            return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors('No se pudo guardar el usuario.')->withInput();
        }
    }

    public function edit(User $usuario): View
    {
        return view('usuarios.edit', [
            'usuario' => $usuario->load('roles'),
            'roles' => $this->roles(),
        ]);
    }

    public function update(Request $request, User $usuario): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'exists:roles,name'],
            'active' => ['nullable', 'boolean'],
        ]);

        try {
            $usuario->forceFill([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'active' => $request->boolean('active', true),
            ]);

            if (! empty($validated['password'])) {
                $usuario->password = Hash::make($validated['password']);
            }

            $usuario->save();
            $usuario->syncRoles([$validated['role']]);

            return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors('No se pudo actualizar el usuario.')->withInput();
        }
    }

    public function destroy(User $usuario): RedirectResponse
    {
        try {
            $usuario->forceFill(['active' => false])->save();

            return redirect()->route('usuarios.index')->with('success', 'Usuario desactivado correctamente.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors('No se pudo desactivar el usuario.');
        }
    }

    public function toggleLock(User $usuario): RedirectResponse
    {
        try {
            $usuario->forceFill([
                'locked_until' => $usuario->locked_until && $usuario->locked_until->isFuture()
                    ? null
                    : now()->addYear(),
            ])->save();

            return back()->with('success', 'Estado de bloqueo actualizado.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors('No se pudo actualizar el bloqueo del usuario.');
        }
    }

    private function roles()
    {
        return Role::whereIn('name', ['Administrador', 'Cajero', 'Inventario', 'Admin', 'Administrador general'])
            ->orderBy('name')
            ->pluck('name');
    }
}
