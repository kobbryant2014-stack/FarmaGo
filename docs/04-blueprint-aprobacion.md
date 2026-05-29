# FarmaGo - Blueprint de aprobacion antes de codigo

Este documento consolida la estructura propuesta antes de generar migraciones, controladores, servicios o pantallas nuevas. La normativa peruana puede cambiar; por eso los catalogos SUNAT/DIGEMID/MINSA, condiciones de venta, reglas de CPE y parametros tributarios deben ser configurables y versionables.

## 1. Resumen ejecutivo

FarmaGo sera una plataforma web empresarial para farmacia/botica peruana. No sera solo POS: integrara operacion comercial, control sanitario, inventario FEFO, caja, compras, facturacion electronica, reportes, auditoria y seguridad.

Objetivos del sistema:

- Vender medicamentos y productos relacionados con control de stock, lote y vencimiento.
- Emitir boletas, facturas, notas de credito y notas de debito electronicas.
- Integrarse con SUNAT directo, OSE, PSE o API externa mediante adaptadores.
- Mantener trazabilidad de inventario por lote, almacen, usuario y origen.
- Controlar medicamentos con receta, receta retenida, receta especial y fiscalizados.
- Administrar caja por turno, medios de pago, arqueos, cierres y diferencias.
- Proteger datos personales y datos sensibles de pacientes/recetas.
- Registrar auditoria completa de operaciones criticas.
- Prepararse para varias sedes, volumen alto de ventas y crecimiento funcional.

## 2. Arquitectura recomendada

Stack objetivo recomendado:

- Backend: Laravel 11+ con PHP 8.2+ para nuevos desarrollos. El proyecto actual esta en Laravel 10/PHP 8.1; se debe planificar upgrade controlado antes de produccion o estabilizar la version actual si el plazo manda.
- Base de datos: PostgreSQL preferido para robustez, constraints, JSONB e indices avanzados. MySQL/MariaDB es aceptable si el entorno XAMPP sera requisito inicial.
- Frontend: Blade/AdminLTE como base inmediata; POS puede evolucionar a Vue/React embebido para experiencia rapida.
- Colas: Laravel Queue con Redis o database queue inicialmente.
- Jobs programados: scheduler para CPE pendientes, backups, alertas y vencimientos.
- Storage: disco privado para XML, PDF, CDR, recetas, certificados y backups.
- Integraciones: contratos/adaptadores para CPE, consulta RUC/DNI, correo, WhatsApp e impresion.

Arquitectura logica:

- Monolito modular por dominios para mantener consistencia entre venta, caja, stock y CPE.
- Capas: Presentacion, Aplicacion, Dominio, Infraestructura, Auditoria.
- Servicios de dominio: StockService, FefoService, VentaService, CompraService, CajaService, CpeService, RecetaService, AuditService.
- Patron Outbox para eventos externos: envio CPE, email, WhatsApp, backups en nube.
- Maquinas de estado para CPE, caja, receta, venta y compra.
- Ledger inmutable para stock y caja: los saldos se derivan de movimientos.

## 3. Modulos definitivos

### 3.1 Seguridad, usuarios y roles

Incluye usuarios, roles, permisos, bloqueo por intentos fallidos, ultima IP, ultimo acceso, sesiones, recuperacion de clave, auditoria de seguridad y politicas de contrasena.

Roles base:

- Administrador general
- Quimico farmaceutico / Director tecnico
- Cajero / Vendedor
- Almacenero
- Contabilidad
- Auditor
- Supervisor
- Empleado sin permisos por defecto

Permisos base:

- Productos: ver, crear, editar, desactivar, cambiar precio
- Inventario: ver, ajustar, transferir, inmovilizar, retirar
- Compras: crear, editar, anular, pagar, devolver
- Ventas: vender, descontar, anular, devolver
- CPE: emitir, reenviar, consultar, emitir notas, configurar SUNAT
- Caja: abrir, mover, cerrar, auditar
- Recetas/controlados: registrar, validar, dispensar, reportar
- Reportes: operativo, tributario, sanitario, gerencial
- Auditoria: ver, exportar
- Sistema: usuarios, roles, configuracion, backups

### 3.2 Productos farmaceuticos

Administra codigo interno, barcode, nombre comercial, DCI, principio activo, concentracion, forma farmaceutica, presentacion, laboratorio, fabricante, registro sanitario, condicion de venta, categoria, unidad, precios, stock minimo/maximo, afectacion tributaria, IGV, flags sanitarios, ubicacion, imagen y observaciones.

Busquedas optimizadas:

- Codigo de barras
- Nombre comercial
- DCI
- Principio activo
- Laboratorio
- Registro sanitario
- Categoria/subcategoria

### 3.3 Inventario, kardex, lotes y vencimientos

Controla stock por producto, lote, almacen/sede y estado sanitario. Incluye ingresos, salidas, ajustes, transferencias, productos vencidos, por vencer, inmovilizados, retirados, kardex fisico y valorizado.

Reglas:

- No venta de vencidos, sin stock o inmovilizados.
- Alertas a 30, 60 y 90 dias.
- FEFO obligatorio para salida automatica.
- Ajustes con usuario, motivo y autorizacion.
- Movimientos no se eliminan; se reversan/anulan con motivo.

### 3.4 Compras y proveedores

Incluye proveedores, ordenes de compra, facturas de compra, lote y vencimiento por item, costos, descuentos, IGV, total, cuentas por pagar, pagos y devoluciones.

### 3.5 Ventas POS

POS rapido para mostrador:

- Venta por barcode, nombre, DCI o principio activo.
- Lote automatico FEFO y lote manual con permiso.
- Unidad, caja, blister y fraccion si aplica.
- Descuentos/promociones autorizadas.
- Pagos: efectivo, tarjeta, Yape, Plin, transferencia, credito, mixto.
- Vuelto.
- Boleta, factura, ticket, PDF A4, ticketera 58/80 mm.
- Envio por correo/WhatsApp.
- Cliente sin documento, DNI, RUC, frecuente o paciente.

### 3.6 Facturacion electronica SUNAT

Incluye configuracion de empresa, RUC, razon social, direccion fiscal, ubigeo, certificado digital, series, correlativos, proveedor CPE, XML, firma, PDF, QR, CDR, reenvio, consulta, resumen diario de boletas si aplica, comunicacion de baja si aplica, nota de credito, nota de debito y guia de remision si corresponde.

Estados:

- pendiente
- generado
- firmado
- enviado
- aceptado
- observado
- rechazado
- anulado
- con_nota_credito
- error_conexion
- pendiente_reenvio

Reglas:

- No modificar CPE aceptado.
- Correcciones mediante nota de credito/debito.
- Guardar XML, PDF, QR payload, CDR y respuesta completa.
- Permitir contingencia y reintentos.
- Series/correlativos unicos por empresa, sucursal, tipo y serie.

### 3.7 Recetas medicas

Registra receta, archivo, numero, fecha emision/vencimiento, medico, CMP, paciente, producto prescrito, dosis, cantidad, frecuencia, observaciones, tipo y estado.

Tipos:

- simple
- retenida
- especial

Estados:

- registrada
- validada
- observada
- usada
- parcialmente_usada
- vencida
- anulada

Reglas:

- Producto con receta no se vende sin receta.
- Receta retenida queda marcada y asociada.
- Controlado exige datos completos y autorizacion.
- Permitir dispensacion parcial.
- Validacion del quimico farmaceutico registrada.

### 3.8 Medicamentos controlados o fiscalizados

Controla productos controlados, receta especial, paciente, medico, cantidades, saldo exacto por lote, libro digital, entradas, salidas, devoluciones, mermas, ajustes autorizados y reportes para fiscalizacion.

### 3.9 Caja y tesoreria

Incluye apertura, monto inicial, ventas por turno, ingresos, egresos, gastos menores, devoluciones, cierre, arqueo, diferencia, cierre Z, reportes y bloqueo de venta sin caja abierta.

### 3.10 Clientes y pacientes

Administra tipo de documento, DNI, RUC, CE, nombres, apellidos, razon social, direccion, telefono, correo, fecha nacimiento, historial de compras, historial de recetas, frecuente y fidelizacion opcional.

Proteccion:

- Acceso por rol.
- Enmascaramiento de datos.
- Consentimiento si se usa fidelizacion/marketing.
- Datos de salud como sensibles.

### 3.11 Devoluciones, anulaciones y notas

Incluye devolucion total/parcial, anulacion, nota de credito electronica, reintegro a stock si corresponde, bloqueo de productos vencidos devueltos, motivo, autorizador, impacto en caja e inventario.

### 3.12 Reportes

Reportes:

- Ventas diarias/mensuales/cajero/producto/categoria/laboratorio
- Productos mas y menos vendidos
- Utilidad bruta y margen
- Stock actual, critico, vencido, por vencer
- Kardex
- Compras, proveedores, cuentas por pagar
- Cierre de caja
- CPE aceptados/rechazados/pendientes/notas
- Medicamentos con receta/controlados
- Auditoria
- Contabilidad, administracion y fiscalizacion sanitaria

Exportacion:

- PDF
- Excel
- CSV

### 3.13 Alertas automaticas

Alertas:

- Sin stock
- Stock minimo/critico
- Por vencer
- Vencido
- Inmovilizado
- CPE rechazado/pendiente
- Caja abierta sin cierre
- Receta vencida
- Controlado con diferencia de stock
- Proveedor con deuda
- Compra pendiente de pago
- Backup pendiente/fallido
- Producto sin registro sanitario
- Producto sin lote

### 3.14 Auditoria

Registra login, logout, usuarios, permisos, productos, precios, stock, ventas, anulaciones, notas, compras, devoluciones, configuracion SUNAT, reenvios, errores, backups y eliminacion logica.

Campos de auditoria:

- usuario
- accion
- modulo
- entidad
- fecha/hora
- IP
- dispositivo/user-agent
- datos anteriores
- datos nuevos
- motivo
- estado

### 3.15 Configuracion general

Empresa, RUC, razon social, logo, moneda, IGV, series, impresoras, ticketera, certificado digital, proveedor CPE, vencimientos, stock minimo, backups, formatos, mensajes, horarios y sucursales.

### 3.16 Backup y mantenimiento

Backups manuales y automaticos, nube, restauracion, registro de backups, limpieza temporal, logs, exportacion DB, verificacion de integridad y control de acceso.

## 4. Base de datos propuesta

La base debe normalizar maestros, movimientos y documentos. Tablas principales:

- users, roles, permissions y pivots de Spatie
- companies, branches, warehouses, settings
- product_categories, product_subcategories, laboratories, active_ingredients, product_presentations, products, product_barcodes, product_prices
- product_lots, stock_movements, inventory_adjustments, inventory_transfers
- suppliers, purchases, purchase_details, supplier_payments, supplier_returns
- customers, patients, doctors, prescriptions, prescription_details
- sales, sale_details, sale_payments
- electronic_documents, electronic_document_items, electronic_document_files, electronic_document_events
- credit_notes, debit_notes, document_series, sunat_configurations
- cash_registers, cash_sessions, cash_movements, cash_count_details
- controlled_medicines, controlled_medicine_movements
- returns, return_details
- audit_logs
- alerts
- backups

Campos transversales:

- id
- timestamps
- status/estado
- created_by
- updated_by
- deleted_at cuando aplique
- foreign keys
- indices de busqueda y unicidad

## 5. Diagrama logico de funcionamiento

```text
Usuario autenticado
  -> Rol/permisos
  -> Sucursal y caja
  -> Modulo operativo
     -> Validaciones de negocio
     -> Transaccion DB
     -> Movimientos inmutables
     -> Auditoria
     -> Jobs externos si aplica
        -> SUNAT/OSE/PSE
        -> Correo/WhatsApp
        -> Backups/reportes
```

## 6. Flujo principal de venta

1. Usuario abre caja.
2. Entra al POS.
3. Busca producto por barcode, nombre, DCI o principio activo.
4. Sistema muestra stock por lote.
5. Sistema selecciona lote FEFO.
6. Valida stock, vencimiento, inmovilizacion y condicion de venta.
7. Si requiere receta, solicita receta.
8. Si es controlado, exige receta especial/autorizacion.
9. Usuario agrega productos al carrito.
10. Sistema calcula subtotal, IGV, descuentos y total.
11. Usuario selecciona cliente.
12. Usuario selecciona comprobante.
13. Usuario registra pagos y vuelto.
14. Sistema registra venta, pago, salida de stock y kardex.
15. Sistema genera CPE/ticket.
16. Sistema envia a SUNAT/OSE/PSE o cola contingencia.
17. Sistema guarda XML, PDF, QR, CDR.
18. Sistema imprime/envia comprobante.
19. Sistema registra auditoria.

## 7. Flujo principal de compra

1. Usuario registra o selecciona proveedor.
2. Crea compra/orden.
3. Ingresa comprobante del proveedor.
4. Agrega productos.
5. Registra lote y vencimiento por item.
6. Sistema calcula costos, descuentos, IGV y total.
7. Sistema registra compra.
8. Sistema crea lotes.
9. Sistema actualiza stock por movimientos.
10. Sistema registra kardex.
11. Sistema genera cuenta por pagar.
12. Sistema registra auditoria.

## 8. Flujo de facturacion electronica

1. Se confirma venta.
2. Se selecciona boleta o factura.
3. Sistema valida cliente y tipo de documento.
4. Sistema reserva serie/correlativo.
5. Sistema genera XML.
6. Sistema firma XML.
7. Sistema genera PDF y QR.
8. Sistema envia a SUNAT/OSE/PSE.
9. Si acepta: guarda CDR y marca aceptado.
10. Si rechaza: guarda error y bloquea modificacion directa.
11. Si falla conexion: queda pendiente de reenvio.
12. Sistema permite consulta/reenvio.
13. Sistema audita cada transicion.

## 9. Reglas de negocio criticas

- No vender productos vencidos.
- No vender productos sin stock.
- No vender productos inmovilizados.
- No vender medicamentos con receta sin receta.
- No vender controlados sin receta especial/autorizacion.
- No modificar comprobantes electronicos aceptados.
- No eliminar ventas, CPE ni movimientos de stock.
- Anulaciones/devoluciones con motivo y nota si corresponde.
- No vender sin caja abierta.
- Advertir cierre con CPE pendientes.
- No editar precios sin permiso.
- Aplicar FEFO.
- Guardar XML/PDF/CDR.
- Validar DNI/RUC segun comprobante.
- RUC obligatorio para factura.
- Proteger datos personales y datos de salud.
- Bloquear usuarios inactivos.
- Auditoria completa por usuario, fecha, hora, IP y dispositivo.

## 10. Interfaces propuestas

Pantallas:

- Login
- Dashboard
- POS
- Productos
- Nuevo/editar producto
- Inventario
- Lotes y vencimientos
- Compras
- Proveedores
- Clientes/pacientes
- Recetas
- Medicamentos controlados
- Caja
- Ventas
- Comprobantes electronicos
- Notas de credito/debito
- Reportes
- Usuarios/roles
- Configuracion
- Auditoria
- Backups

Dashboard:

- Ventas del dia
- Ventas del mes
- Caja actual
- Stock critico
- Productos por vencer
- Productos vencidos
- CPE pendientes/rechazados
- Productos mas vendidos
- Alertas sanitarias
- Alertas de inventario
- Backups pendientes

## 11. Plan por etapas

1. Analisis y alcance: aprobar este blueprint.
2. Base de datos: ER, diccionario, migraciones y seeders.
3. Backend core: seguridad, usuarios, empresas, sucursales, auditoria.
4. Productos e inventario: catalogos, lotes, FEFO, kardex.
5. Compras y proveedores: compras, lotes, cuentas por pagar.
6. Ventas POS y caja: carrito, pagos, caja, stock.
7. CPE: XML, firma, PDF, QR, CDR, proveedor SUNAT/OSE/PSE.
8. Recetas y controlados: dispensacion, libro digital, reportes.
9. Reportes y alertas.
10. Backup, hardening y despliegue.
11. Pruebas funcionales, seguridad, CPE e inventario.

## 12. Criterio para pasar a codigo

Se debe aprobar:

- Stack objetivo: Laravel 11+/PHP 8.2 o continuar temporalmente con Laravel 10/PHP 8.1.
- Motor de base de datos: PostgreSQL o MySQL/MariaDB.
- Proveedor CPE inicial: SUNAT directo, OSE, PSE o API externa.
- Alcance de guia de remision: fase inicial o fase posterior.
- Alcance multi-sucursal: desde fase 2 o preparado para futuro.
- Nivel inicial de frontend POS: Blade/AdminLTE o componente Vue/React.
