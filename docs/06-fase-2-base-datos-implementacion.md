# FarmaGo - Fase 2: Implementacion de base de datos

## 1. Objetivo

La Fase 2 deja preparada la estructura relacional para operar FarmaGo como plataforma integral de farmacia/botica en Peru. La implementacion amplia el sistema existente sin eliminar tablas historicas y agrega soporte para empresa, sucursales, almacenes, facturacion electronica, caja, recetas, medicamentos controlados, devoluciones, alertas, auditoria y backups.

Nota importante: el modelo usa nombres de tablas existentes en espanol cuando el proyecto ya los tenia (`productos`, `ventas`, `lotes`, `clientes`, etc.). Los documentos conceptuales mantienen nombres equivalentes en ingles para arquitectura objetivo, pero la implementacion respeta el codigo actual.

## 2. Migraciones creadas

### Core operativo

Archivo: `2026_05_29_150000_create_phase_two_core_tables.php`

Tablas:

- `empresas`: datos del emisor, RUC, razon social, direccion fiscal, moneda, IGV y estado.
- `sucursales`: locales por empresa, codigo unico por empresa, direccion y ubigeo.
- `almacenes`: almacenes por sucursal para stock multi-sede.
- `configuraciones_sistema`: parametros generales por empresa/sucursal.

### Catalogos

Archivo: `2026_05_29_150100_create_phase_two_catalog_tables.php`

Tablas:

- `tipos_documento_identidad`: DNI, RUC, carne de extranjeria y otros catalogos de identidad.
- `tipos_comprobante`: factura, boleta, nota de credito, nota de debito y guia si aplica.
- `catalogos_sunat`: catalogos flexibles para codigos SUNAT.
- `laboratorios`: maestros de laboratorios/fabricantes.
- `principios_activos`: DCI/principios activos normalizados.
- `presentaciones_producto`: unidades y presentaciones comerciales.
- `metodos_pago`: efectivo, tarjeta, Yape, Plin, transferencia, credito y pago mixto.

### Extension de tablas existentes

Archivo: `2026_05_29_150200_extend_existing_tables_for_phase_two.php`

Tablas extendidas:

- `users`: sucursal, ultimo acceso, IP, intentos fallidos y bloqueo temporal.
- `proveedores`: documento, razon social, estado y auditoria de creacion/modificacion.
- `clientes`: tipo de documento, datos personales, fidelizacion, consentimiento de datos y estado.
- `productos`: codigo interno, DCI, principio activo, concentracion, forma farmaceutica, presentacion, laboratorio, registro sanitario, condicion de venta, flags sanitarios, precios por unidad/caja/blister, ubicacion e imagen.
- `compras`: empresa, sucursal, datos del comprobante del proveedor, totales tributarios y estado de pago.
- `lotes`: almacen, fabricacion, estado sanitario/operativo y motivo de bloqueo.
- `detalle_compra`: descuento, IGV y total por item.
- `ventas`: empresa, sucursal, desglose tributario, descuento total y cierre de caja.
- `detalle_venta`: receta asociada, unidad de venta, descuento, afectacion tributaria, IGV y total.
- `movimientos_inventario`: almacen, estado y movimiento reversado.

### Caja y pagos

Archivo: `2026_05_29_150300_create_phase_two_cash_and_payment_tables.php`

Tablas:

- `cajas`: cajas fisicas o logicas por sucursal.
- `sesiones_caja`: apertura, cierre, monto inicial, esperado, contado y diferencia.
- `movimientos_caja`: ingresos, egresos, ventas, devoluciones, retiros y ajustes.
- `detalles_arqueo_caja`: arqueo por metodo de pago.
- `pagos_venta`: pagos de ventas con soporte para pagos mixtos.
- `pagos_proveedor`: pagos de compras/cuentas por pagar.

### Facturacion electronica

Archivo: `2026_05_29_150400_create_phase_two_electronic_billing_tables.php`

Tablas:

- `configuraciones_sunat`: proveedor SUNAT/OSE/PSE/API, ambiente beta/produccion, endpoints y referencias a credenciales cifradas.
- `series_documentos`: series y correlativos por empresa, sucursal y tipo de comprobante.
- `comprobantes_electronicos`: cabecera tributaria, estados CPE, totales, QR, hash XML y respuesta SUNAT/OSE/PSE.
- `comprobante_items`: detalle tributario del comprobante.
- `comprobante_archivos`: XML, XML firmado, PDF, ticket y CDR con hash.
- `comprobante_eventos`: bitacora tecnica de estados, request, response y errores.
- `notas_credito`: notas de credito relacionadas a CPE original.
- `notas_debito`: notas de debito relacionadas a CPE original.

### Recetas y control sanitario

Archivo: `2026_05_29_150500_create_phase_two_sanitary_control_tables.php`

Tablas:

- `medicos`: medico prescriptor, CMP y especialidad.
- `pacientes`: datos del paciente, separado del cliente comprador cuando aplique.
- `receta_detalles`: productos prescritos, dosis, cantidad y saldo dispensado.
- `medicamentos_controlados`: configuracion sanitaria por producto controlado.
- `movimientos_medicamento_controlado`: libro digital de entradas, salidas, devoluciones, mermas y ajustes.

Tambien se extendio `recetas` con paciente, medico, vencimiento, tipo de receta, validacion del quimico farmaceutico, adjuntos y estado.

### Auditoria, devoluciones, alertas y mantenimiento

Archivo: `2026_05_29_150600_create_phase_two_audit_alert_return_tables.php`

Tablas:

- `ajustes_inventario`: solicitudes/aprobaciones de ajustes con motivo obligatorio.
- `transferencias_inventario`: cabecera de transferencias entre almacenes.
- `transferencia_inventario_detalles`: detalle de productos/lotes transferidos.
- `devoluciones`: devoluciones totales/parciales con impacto en caja e inventario.
- `devolucion_detalles`: items devueltos y estado de reintegro a stock.
- `alertas`: alertas de stock, vencimiento, CPE, caja, recetas, backups y control sanitario.
- `audit_logs`: auditoria transversal con valores anteriores/nuevos, IP, usuario y motivo.
- `backups`: control de backups manuales/automaticos, hash, estado y errores.

## 3. Llaves, indices y restricciones relevantes

- Empresa emisora con `ruc` unico.
- Sucursal con codigo unico por empresa.
- Almacen con codigo unico por sucursal.
- Producto con `codigo_interno`, `codigo_barra`, DCI, principio activo, registro sanitario, estado e indices de busqueda POS.
- Lote indexado por producto, almacen, vencimiento y estado para FEFO.
- Serie CPE unica por empresa, sucursal, tipo de comprobante y serie.
- Comprobante electronico unico por empresa, tipo, serie y numero.
- Pagos de venta vinculados a metodo de pago y venta.
- Sesion de caja abierta/cerrada con importes separados para arqueo.
- Auditoria indexada por usuario, modulo y entidad.

## 4. Catalogos sembrados

Seeder: `PhaseTwoCatalogSeeder`

Incluye:

- Tipos de documento: DNI, RUC, carne de extranjeria, pasaporte y no domiciliado.
- Tipos de comprobante: factura, boleta, nota de credito, nota de debito y guia.
- Metodos de pago: efectivo, tarjeta, Yape, Plin, transferencia, credito y pago mixto.
- Empresa demo FarmaGo con RUC ficticio `00000000000`.
- Sucursal y almacen principal demo.
- Caja principal demo.
- Configuracion SUNAT beta inactiva, sin credenciales reales.
- Series demo para factura, boleta, nota de credito y nota de debito.

## 5. Reglas soportadas por estructura

- Bloqueo futuro de venta por producto/lote vencido, inmovilizado o sin stock.
- FEFO mediante indices por `producto_id`, `fecha_vencimiento` y `estado`.
- Caja obligatoria mediante relacion `ventas.sesion_caja_id`.
- Pagos mixtos mediante `pagos_venta`.
- Receta obligatoria mediante `detalle_venta.receta_id` y flags de producto.
- Medicamento controlado mediante `medicamentos_controlados` y movimientos estrictos.
- CPE inmutable a nivel funcional mediante tabla separada de comprobantes, eventos y archivos.
- XML/PDF/CDR trazables mediante `comprobante_archivos`.
- Anulaciones/devoluciones sin eliminacion fisica mediante `devoluciones` y notas.
- Auditoria transversal mediante `audit_logs`.

## 6. Validacion ejecutada

Comandos validados:

```bash
php -l database\seeders\PhaseTwoCatalogSeeder.php
php -l database\seeders\DatabaseSeeder.php
APP_ENV=testing DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan migrate:fresh --seed --force
php artisan test
```

Resultado:

- Migraciones y seeders ejecutados correctamente en SQLite en memoria.
- Suite actual: 23 pruebas pasadas.
- Advertencias existentes: `.env` ausente para algunas pruebas y esquema PHPUnit antiguo. No bloquean la Fase 2.

## 7. Pendientes para fases siguientes

- Crear modelos Eloquent especificos para las nuevas tablas.
- Crear servicios de dominio: `CajaService`, `CpeService`, `FefoStockService`, `AuditService`.
- Implementar policies/middlewares por modulo.
- Agregar validaciones de negocio en backend, no solo estructura de base de datos.
- Cifrar efectivamente certificados, claves y datos sensibles.
- Implementar jobs de envio CPE y almacenamiento privado de XML/PDF/CDR.
- Crear pruebas de integridad especificas para FEFO, caja, CPE, recetas y controlados.
