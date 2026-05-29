<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\AuditService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'active' => true,
        ]);

        $defaultRole = Role::firstOrCreate(['name' => 'Empleado', 'guard_name' => 'web']);
        $user->assignRole($defaultRole);

        event(new Registered($user));

        Auth::login($user);

        app(AuditService::class)->record('usuario_registrado', 'seguridad', [
            'user_id' => $user->id,
            'entidad_tipo' => 'users',
            'entidad_id' => $user->id,
            'datos_nuevos' => [
                'email' => $user->email,
                'rol_inicial' => 'Empleado',
            ],
        ]);

        return redirect(RouteServiceProvider::HOME);
    }
}
