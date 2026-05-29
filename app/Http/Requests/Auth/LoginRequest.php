<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Services\AuditService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $user = User::where('email', (string) $this->string('email'))->first();

        if ($user && ! $user->active) {
            $this->auditLoginAttempt('login_bloqueado_usuario_inactivo', $user);

            throw ValidationException::withMessages([
                'email' => 'La cuenta no esta disponible. Contacte al administrador.',
            ]);
        }

        if ($user && $user->locked_until && $user->locked_until->isFuture()) {
            $this->auditLoginAttempt('login_bloqueado_temporalmente', $user);

            throw ValidationException::withMessages([
                'email' => 'La cuenta esta bloqueada temporalmente. Intente nuevamente mas tarde.',
            ]);
        }

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
            $this->registerFailedAttempt($user);

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $this->clearFailedAttempts(Auth::user());
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }

    private function registerFailedAttempt(?User $user): void
    {
        if (! $user) {
            app(AuditService::class)->record('login_fallido_usuario_no_encontrado', 'seguridad', [
                'datos_nuevos' => [
                    'email' => (string) $this->string('email'),
                ],
                'estado' => 'fallido',
            ]);

            return;
        }

        $attempts = $user->failed_login_attempts + 1;
        $attributes = [
            'failed_login_attempts' => $attempts,
        ];

        if ($attempts >= 5) {
            $attributes['locked_until'] = now()->addMinutes(15);
        }

        $user->forceFill($attributes)->save();

        $this->auditLoginAttempt(
            $attempts >= 5 ? 'login_fallido_usuario_bloqueado' : 'login_fallido',
            $user,
            [
                'failed_login_attempts' => $attempts,
                'locked_until' => $attributes['locked_until'] ?? null,
            ],
            'fallido'
        );
    }

    private function clearFailedAttempts(?User $user): void
    {
        if (! $user) {
            return;
        }

        $user->forceFill([
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ])->save();
    }

    private function auditLoginAttempt(
        string $accion,
        ?User $user,
        array $datosNuevos = [],
        string $estado = 'registrado'
    ): void {
        app(AuditService::class)->record($accion, 'seguridad', [
            'user_id' => $user?->id,
            'entidad_tipo' => $user ? 'users' : null,
            'entidad_id' => $user?->id,
            'datos_nuevos' => $datosNuevos + [
                'email' => (string) $this->string('email'),
            ],
            'estado' => $estado,
        ]);
    }
}
