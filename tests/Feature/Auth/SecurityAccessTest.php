<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_inactive_users_can_not_authenticate(): void
    {
        $user = User::factory()->create([
            'active' => false,
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'accion' => 'login_bloqueado_usuario_inactivo',
            'modulo' => 'seguridad',
        ]);
    }

    public function test_locked_users_can_not_authenticate(): void
    {
        $user = User::factory()->create([
            'locked_until' => now()->addMinutes(10),
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'accion' => 'login_bloqueado_temporalmente',
            'modulo' => 'seguridad',
        ]);
    }

    public function test_successful_login_updates_access_metadata_and_audit(): void
    {
        $user = User::factory()->create([
            'failed_login_attempts' => 2,
            'locked_until' => null,
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $user->refresh();

        $this->assertNotNull($user->last_login_at);
        $this->assertNotNull($user->last_login_ip);
        $this->assertSame(0, $user->failed_login_attempts);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'accion' => 'login_exitoso',
            'modulo' => 'seguridad',
        ]);
    }
}
