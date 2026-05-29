<?php

namespace App\Http\Middleware;

use App\Services\AuditService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserAccountIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        $locked = $user->locked_until && $user->locked_until->isFuture();

        if ($user->active === false || $locked) {
            app(AuditService::class)->record('sesion_finalizada_por_cuenta_no_disponible', 'seguridad', [
                'user_id' => $user->id,
                'entidad_tipo' => 'users',
                'entidad_id' => $user->id,
                'motivo' => $locked ? 'Usuario bloqueado temporalmente' : 'Usuario desactivado',
            ]);

            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors(['email' => 'La cuenta no esta disponible. Contacte al administrador.']);
        }

        return $next($request);
    }
}
