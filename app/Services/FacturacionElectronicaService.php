<?php

namespace App\Services;

use App\Models\ComprobanteElectronico;
use App\Models\Empresa;
use App\Models\SerieDocumento;
use App\Models\Sucursal;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FacturacionElectronicaService
{
    public function emitirDesdeVenta(Venta $venta, string $tipoComprobante = '03'): ComprobanteElectronico
    {
        if (! in_array($tipoComprobante, ['01', '03'], true)) {
            throw ValidationException::withMessages([
                'tipo_comprobante' => 'Solo se admite Factura electronica (01) o Boleta electronica (03).',
            ]);
        }

        return DB::transaction(function () use ($venta, $tipoComprobante) {
            $venta->load(['cliente', 'detalles.producto']);

            $empresa = Empresa::activas()->firstOrFail();
            $sucursal = Sucursal::where('empresa_id', $empresa->id)->where('activo', true)->first();

            $existente = ComprobanteElectronico::where('venta_id', $venta->id)
                ->where('tipo_comprobante', $tipoComprobante)
                ->first();

            if ($existente) {
                return $existente->load(['empresa', 'sucursal', 'cliente', 'venta', 'items.producto']);
            }

            if ($tipoComprobante === '01' && ! $venta->cliente_id) {
                throw ValidationException::withMessages([
                    'cliente_id' => 'Para emitir factura electronica SUNAT se requiere cliente identificado.',
                ]);
            }

            $serie = SerieDocumento::where('empresa_id', $empresa->id)
                ->where('sucursal_id', $sucursal?->id)
                ->where('tipo_comprobante', $tipoComprobante)
                ->where('activo', true)
                ->lockForUpdate()
                ->firstOrFail();

            $serie->increment('correlativo_actual');
            $numero = $serie->correlativo_actual;

            $qrPayload = implode('|', [
                $empresa->ruc,
                $tipoComprobante,
                $serie->serie,
                $numero,
                number_format((float) $venta->igv_total, 2, '.', ''),
                number_format((float) $venta->total, 2, '.', ''),
                $venta->fecha?->format('Y-m-d') ?? now()->toDateString(),
                $venta->cliente?->tipo_documento ?? '-',
                $venta->cliente?->documento ?? '-',
            ]);

            $comprobante = ComprobanteElectronico::create([
                'empresa_id' => $empresa->id,
                'sucursal_id' => $sucursal?->id,
                'venta_id' => $venta->id,
                'cliente_id' => $venta->cliente_id,
                'tipo_comprobante' => $tipoComprobante,
                'serie' => $serie->serie,
                'numero' => $numero,
                'fecha_emision' => $venta->fecha?->toDateString() ?? now()->toDateString(),
                'moneda_codigo' => $empresa->moneda_codigo,
                'subtotal' => $venta->subtotal,
                'igv_total' => $venta->igv_total,
                'total' => $venta->total,
                'qr_payload' => $qrPayload,
                'xml_hash' => hash('sha256', $qrPayload.'|'.$venta->id),
                'tipo_proveedor' => 'sunat',
                'estado' => 'pendiente',
                'estado_sunat' => 'pendiente_envio',
                'respuesta_proveedor' => [
                    'modo' => 'registro_local',
                    'nota' => 'Pendiente de envio real a SUNAT/PSE/OSE con certificado digital y credenciales SOL.',
                ],
            ]);

            foreach ($venta->detalles as $detalle) {
                $comprobante->items()->create([
                    'producto_id' => $detalle->producto_id,
                    'descripcion' => $detalle->producto->nombre ?? 'Producto',
                    'cantidad' => $detalle->cantidad,
                    'precio_unitario' => $detalle->precio_unitario,
                    'afectacion_tributaria' => $detalle->afectacion_tributaria ?? '10',
                    'igv' => $detalle->igv,
                    'total' => $detalle->total,
                ]);
            }

            app(AuditService::class)->record('emision_comprobante_electronico_local', 'facturacion', [
                'entidad_tipo' => 'comprobantes_electronicos',
                'entidad_id' => $comprobante->id,
                'datos_nuevos' => [
                    'tipo_comprobante' => $tipoComprobante,
                    'serie' => $serie->serie,
                    'numero' => $numero,
                    'estado' => 'pendiente',
                ],
            ]);

            return $comprobante->load(['empresa', 'sucursal', 'cliente', 'venta', 'items.producto']);
        });
    }
}
