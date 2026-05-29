<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Throwable;

class AuditService
{
    public function record(string $accion, string $modulo, array $context = []): void
    {
        try {
            if (! Schema::hasTable('audit_logs')) {
                return;
            }

            DB::table('audit_logs')->insert([
                'user_id' => $context['user_id'] ?? Auth::id(),
                'accion' => $accion,
                'modulo' => $modulo,
                'entidad_tipo' => $context['entidad_tipo'] ?? null,
                'entidad_id' => $context['entidad_id'] ?? null,
                'datos_anteriores' => $this->encodeJson($context['datos_anteriores'] ?? null),
                'datos_nuevos' => $this->encodeJson($context['datos_nuevos'] ?? null),
                'motivo' => $context['motivo'] ?? null,
                'ip_address' => $context['ip_address'] ?? Request::ip(),
                'user_agent' => $context['user_agent'] ?? substr((string) Request::userAgent(), 0, 500),
                'estado' => $context['estado'] ?? 'registrado',
                'created_at' => now(),
            ]);
        } catch (Throwable $exception) {
            Log::warning('No se pudo registrar auditoria.', [
                'accion' => $accion,
                'modulo' => $modulo,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function encodeJson(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
