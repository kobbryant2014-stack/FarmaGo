# FarmaGo - Plan de desarrollo por fases

## Principio de trabajo

Cada fase debe cerrar con:

- Migraciones y modelos consistentes.
- Servicios de dominio con pruebas.
- Controladores/rutas solo despues de reglas probadas.
- Permisos agregados al seed.
- Auditoria minima para acciones criticas.
- Validacion manual de flujo principal.

## Fase 1. Analisis funcional

Estado: completada en esta documentacion inicial.

Entregables:

- Alcance funcional por modulo.
- Arquitectura objetivo.
- Modelo de datos conceptual.
- Flujos principales.
- Reglas de negocio criticas.
- Riesgos regulatorios iniciales.

## Fase 2. Diseno de base de datos

Estado: implementada como base tecnica inicial. Ver `docs/06-fase-2-base-datos-implementacion.md`.

Objetivo:

Redisenar y ampliar las migraciones actuales sin romper lo existente innecesariamente.

Entregables:

- Migraciones nuevas para empresas, sucursales, almacenes, cajas, CPE, pagos, auditoria y controlados.
- Refactor de tablas actuales para company_id/branch_id cuando aplique.
- Indices para busqueda POS: barcode, nombre, lote, vencimiento.
- Llaves unicas de CPE: company_id, document_type, series, number.
- Constraints para cantidades, estados y referencias.
- Diagrama ER actualizado.

Criterios:

- `php artisan migrate:fresh --seed` debe correr.
- Pruebas de integridad basicas pasan.

## Fase 3. Seguridad, usuarios y roles

Estado: implementada como base de seguridad inicial. Ver `docs/07-fase-3-seguridad-usuarios-roles.md`.

Objetivo:

Cerrar control de acceso profesional.

Entregables:

- Roles y permisos definitivos.
- Middlewares/policies por modulo.
- Usuario Super Admin inicial.
- Permisos por sucursal si se habilita multi-local.
- Auditoria de login, logout, cambios de rol y cambios de clave.

## Fase 4. Productos e inventario

Estado: implementada como base backend inicial. Ver `docs/08-fase-4-productos-inventario.md`.

Objetivo:

Inventario trazable por lotes con FEFO.

Entregables:

- CRUD producto/categoria/presentacion.
- CRUD lote controlado por compra o ajuste autorizado.
- Kardex por producto/lote.
- Servicio FEFO para reserva/salida de stock.
- Bloqueo de vencidos.
- Alertas de stock y vencimiento.
- Pruebas de stock y FEFO.

## Fase 5. Compras y proveedores

Objetivo:

Ingreso de mercaderia y lotes confiable.

Entregables:

- CRUD proveedor.
- Registro compra con items/lotes.
- Anulacion/modificacion con reglas de stock.
- Reporte de compras.
- Auditoria de cambios.

## Fase 6. Ventas POS

Objetivo:

POS rapido y seguro.

Entregables:

- Pantalla POS.
- Busqueda por barcode/nombre/principio activo.
- Carrito.
- Pagos mixtos.
- Validacion caja abierta.
- Salida de stock FEFO.
- Venta con receta cuando aplique.
- Pruebas de venta completa.

## Fase 7. Facturacion electronica

Objetivo:

Emision CPE profesional y desacoplada.

Entregables:

- Series y correlativos.
- Modelo CPE.
- Generacion XML UBL.
- Firma digital.
- PDF/ticket y QR.
- Adaptador SUNAT/OSE/PSE.
- Cola de envio y reintentos.
- Almacenamiento XML/PDF/CDR.
- Estados y consulta.
- Notas de credito/debito.

Nota:

Antes de integrar produccion, validar certificados, usuario SOL, ambiente beta, catalogos SUNAT vigentes y proveedor elegido.

## Fase 8. Caja

Objetivo:

Conciliacion de dinero por turno.

Entregables:

- Apertura/cierre.
- Movimientos de caja.
- Arqueo por medio de pago.
- Reporte por caja/cajero.
- Bloqueo POS sin caja.

## Fase 9. Recetas y medicamentos controlados

Objetivo:

Control sanitario y trazabilidad de dispensacion.

Entregables:

- Medicos.
- Recetas.
- Asociacion receta-venta.
- Controlados.
- Reporte de dispensacion.
- Proteccion de datos sensibles.

## Fase 10. Reportes

Objetivo:

Tableros operativos, tributarios y sanitarios.

Entregables:

- Ventas.
- Inventario.
- Vencimientos.
- Compras.
- Caja.
- CPE.
- Controlados.
- Exportacion CSV/XLSX/PDF segun necesidad.

## Fase 11. Auditoria

Objetivo:

Bitacora completa y consultable.

Entregables:

- Tabla audit_events.
- Trait o observer para modelos criticos.
- Eventos de seguridad.
- Eventos CPE.
- Eventos caja.
- Eventos inventario.
- Filtros y exportacion.

## Fase 12. Configuracion

Objetivo:

Administracion sin tocar codigo.

Entregables:

- Empresa, sucursal, almacenes.
- Certificado digital.
- Credenciales SUNAT/OSE/PSE cifradas.
- Series.
- Impuestos y parametros POS.
- Alertas.

## Fase 13. Pruebas

Objetivo:

Confiabilidad de produccion.

Entregables:

- Pruebas unitarias de reglas.
- Pruebas feature de flujos.
- Pruebas de migraciones/seed.
- Pruebas CPE con fixtures XML.
- Pruebas de permisos.
- Auditoria de seguridad basica.

## Fase 14. Despliegue

Objetivo:

Produccion operable.

Entregables:

- `.env.example` completo.
- Guia de instalacion.
- Backups.
- Scheduler.
- Queues.
- Storage privado.
- SSL.
- Hardening de permisos.
- Monitoreo de jobs CPE.
- Plan de contingencia SUNAT/OSE/PSE.

## Backlog tecnico inmediato

1. Construir CRUD visual/API de productos, lotes y catalogos farmaceuticos.
2. Crear servicios: CpeService y CajaService.
3. Construir CRUD administrativo de usuarios, roles y permisos.
4. Agregar observers/eventos de auditoria para operaciones criticas.
5. Implementar validaciones de caja abierta, recetas y CPE.
6. Agregar pruebas especificas de caja, facturacion y control sanitario.
7. Completar `.env.example` para ambientes local, beta CPE y produccion.
