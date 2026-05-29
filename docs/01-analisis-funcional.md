# FarmaGo - Fase 1: Analisis funcional

## 1. Objetivo

FarmaGo sera un sistema web integral para farmacia o botica peruana. Debe cubrir operacion diaria, trazabilidad sanitaria, control tributario, seguridad, caja, auditoria y reportes, preparado para produccion y crecimiento multi-sucursal.

El producto debe soportar:

- Ventas POS rapidas con boleta, factura, nota de credito y nota de debito.
- Emision electronica con XML, firma digital, PDF, QR, CDR y estado SUNAT/OSE/PSE.
- Inventario por producto, lote, vencimiento y movimientos tipo kardex.
- Salida de stock por FEFO: first-expired, first-out.
- Bloqueo de productos vencidos, inactivos, sin stock o sin receta cuando aplique.
- Compras, proveedores, lotes de ingreso y costos.
- Caja: apertura, ingresos, egresos, arqueo y cierre.
- Clientes y datos tributarios.
- Recetas medicas y medicamentos controlados.
- Usuarios, roles, permisos, auditoria y proteccion de datos personales.

## 2. Alcance funcional

### 2.1 Seguridad, usuarios y permisos

Funciones:

- Login, logout, recuperacion de clave y bloqueo por intentos fallidos.
- Usuarios activos/inactivos.
- Roles por responsabilidad operacional.
- Permisos granulares por modulo y accion.
- Auditoria por usuario, IP, navegador, sucursal, caja y entidad afectada.
- Politicas de contrasena y vencimiento configurable.
- Sesiones seguras y revocacion manual.

Roles base:

- Super Admin: configuracion global y seguridad.
- Administrador: operacion completa de una empresa o cadena.
- Quimico Farmaceutico / Director Tecnico: control sanitario, recetas y medicamentos controlados.
- Cajero: ventas POS, caja propia, clientes y consultas de stock.
- Inventario: productos, lotes, compras y ajustes autorizados.
- Contabilidad: CPE, reportes tributarios, caja y anulaciones/notas.
- Auditor: lectura de reportes y bitacoras.
- Empleado: usuario creado sin permisos operativos hasta asignacion formal.

### 2.2 Maestros

Entidades maestras:

- Empresa emisora: RUC, razon social, nombre comercial, direccion fiscal, regimen, certificado digital.
- Sucursal/local: direccion, codigo interno, almacen asociado.
- Series: boleta, factura, nota de credito, nota de debito, guia si se agrega despues.
- Caja y punto de venta.
- Categorias y familias terapeuticas.
- Productos y principios activos.
- Presentaciones, unidades de medida y codigos de barras.
- Clientes: DNI, RUC, CE, pasaporte u otros documentos permitidos.
- Proveedores: RUC, razon social, contacto, estado.
- Medicos/prescriptores para recetas.

### 2.3 Productos e inventario

Funciones:

- Registro de productos con codigo interno, codigo de barras, nombre comercial, concentracion, forma farmaceutica y presentacion.
- Indicadores sanitarios: requiere receta, es controlado, venta fraccionada, requiere cadena de frio, afectacion IGV.
- Stock minimo, maximo y punto de reposicion.
- Lotes con fecha de vencimiento, proveedor, compra, costo y estado.
- Kardex por producto y por lote.
- Ajustes de inventario con autorizacion y motivo obligatorio.
- Alertas de stock bajo, vencimiento proximo y vencidos con stock.
- Bloqueo de salida para productos vencidos o lotes inactivos.
- Seleccion FEFO automatica en POS.

Reglas principales:

- El stock no se edita directamente; se deriva de movimientos.
- Todo movimiento debe tener origen, origen_id, usuario y motivo.
- Un lote vencido no se vende aunque tenga stock.
- Un lote inactivo no se vende.
- Para venta POS, el sistema debe proponer lotes ordenados por vencimiento ascendente.
- Ajustes negativos no pueden dejar stock final menor a cero.
- Productos controlados requieren receta validada y auditoria reforzada.

### 2.4 Compras y proveedores

Funciones:

- Orden/registro de compra a proveedor.
- Ingreso por documento proveedor: factura, boleta, guia, nota.
- Creacion de lotes desde la compra.
- Registro de costo unitario y costo total.
- Anulacion o modificacion controlada cuando no hubo salida de stock.
- Cuentas por pagar basicas como fase posterior.

Reglas principales:

- No se puede anular una compra si algun lote ya tuvo ventas, salvo proceso de devolucion/ajuste autorizado.
- Numero de lote debe ser unico por producto.
- Proveedor con RUC debe validarse estructuralmente y opcionalmente contra padron/RUC via servicio externo.
- Toda modificacion debe dejar historial y relacion con el registro reemplazado.

### 2.5 Ventas POS

Funciones:

- Busqueda por codigo de barras, nombre, principio activo o lote.
- Carrito rapido con cantidades, descuentos autorizados e impuestos.
- Venta a publico general para boleta dentro de limites operativos definidos.
- Venta con DNI/RUC cuando aplique.
- Emision de boleta/factura electronica.
- Medios de pago: efectivo, tarjeta, transferencia, Yape/Plin, mixto.
- Apertura de caja obligatoria para vender.
- Anulacion operativa mediante nota de credito cuando ya existe CPE aceptado.
- Reimpresion de PDF/ticket.

Reglas principales:

- Toda venta debe estar asociada a caja abierta y usuario.
- Factura requiere RUC valido y datos del cliente.
- Boleta puede usar cliente generico segun politicas, pero si supera umbrales tributarios o requiere identificacion, debe exigir documento.
- El total de pagos debe cuadrar con el total de venta.
- No se confirma venta si no hay stock FEFO disponible.
- No se permite cambiar una venta aceptada por SUNAT; se corrige con nota.

### 2.6 Facturacion electronica Peru

Funciones:

- Generar comprobantes electronicos: factura, boleta, nota de credito y nota de debito.
- Generar XML UBL segun tipo de comprobante.
- Firmar XML con certificado digital.
- Generar representacion PDF/A o PDF imprimible y ticket.
- Generar QR de representacion impresa.
- Enviar a SUNAT directo, OSE o PSE mediante adaptador configurable.
- Recibir y almacenar CDR.
- Gestionar estados: borrador, generado, firmado, enviado, aceptado, observado, rechazado, anulado, pendiente_reenvio.
- Reintentos con cola y outbox.
- Consulta de estado.
- Descarga y almacenamiento legal de XML, PDF y CDR.

Reglas principales:

- La numeracion serie-correlativo debe ser unica por empresa, local, tipo y serie.
- La generacion de CPE debe ser atomica respecto a la venta.
- Si falla el envio tributario, la venta queda registrada y el CPE queda pendiente de envio, segun politica permitida.
- Un CPE rechazado requiere correccion y nueva emision si corresponde.
- Un CPE aceptado solo se corrige con nota de credito/debito.
- El proveedor SUNAT/OSE/PSE debe ser intercambiable sin cambiar ventas ni inventario.

### 2.7 Caja

Funciones:

- Apertura con monto inicial.
- Movimientos de caja: ventas, ingresos manuales, egresos, retiros, anulaciones y ajustes.
- Arqueo por medio de pago.
- Cierre con diferencias y motivo obligatorio.
- Reporte de caja por usuario, sucursal y turno.

Reglas principales:

- Un usuario solo puede tener una caja abierta por local/punto de venta.
- No se puede vender sin caja abierta.
- Cierre bloquea nuevos movimientos.
- Diferencias de caja deben auditarse.

### 2.8 Recetas y medicamentos controlados

Funciones:

- Registro de receta: numero, fecha, medico, colegiatura, paciente, diagnostico opcional, archivo adjunto opcional.
- Asociacion receta-venta y receta-productos.
- Validacion de receta requerida antes de venta.
- Control reforzado para productos psicotropicos/estupefacientes o de venta restringida.
- Reporte de dispensacion de controlados.

Reglas principales:

- Producto marcado como requiere_receta no puede venderse sin receta asociada.
- Producto controlado requiere permiso especial para vender y registrar datos minimos de receta.
- Receta usada debe quedar vinculada a venta, cliente/paciente, usuario y fecha.
- Datos de salud y receta se tratan como datos sensibles.

### 2.9 Clientes y proteccion de datos

Funciones:

- Registro minimo por tipo de comprobante.
- Consentimiento y finalidad cuando se almacenen datos no estrictamente necesarios.
- Historial de ventas por cliente con acceso restringido.
- Enmascaramiento parcial de documentos para roles no autorizados.
- Exportacion/eliminacion logica segun politica legal y contable.

Reglas principales:

- Datos personales se recolectan por finalidad: facturacion, garantia, receta o fidelizacion.
- No se muestran datos sensibles a cajeros fuera del flujo necesario.
- Eliminacion fisica no aplica a documentos tributarios o auditoria obligatoria; se usa anonimizado cuando corresponda.

### 2.10 Reportes

Reportes operativos:

- Ventas por dia, cajero, caja, producto, categoria y medio de pago.
- Utilidad estimada por venta/producto/lote.
- Stock actual, bajo stock, vencidos y proximos a vencer.
- Kardex por producto/lote.
- Compras por proveedor y periodo.
- Caja por turno.

Reportes tributarios:

- CPE emitidos por tipo, serie, estado y periodo.
- Pendientes de envio o con error.
- Notas de credito/debito asociadas.
- Resumen de ventas por IGV/exonerado/inafecto si aplica.

Reportes sanitarios:

- Dispensacion de productos con receta.
- Dispensacion de controlados.
- Vencidos retirados o bloqueados.

### 2.11 Auditoria

Funciones:

- Registro inmutable de creacion, actualizacion, anulacion, login, cambio de permisos, caja, CPE, inventario y receta.
- Antes/despues para cambios criticos.
- Hash de evento opcional para detectar manipulacion.
- Consulta por entidad, usuario, modulo, fecha, IP y sucursal.

Reglas principales:

- No se elimina auditoria desde la aplicacion.
- Cambios de precio, stock, permisos, documentos tributarios y recetas siempre generan evento.
- Acceso a auditoria requiere permiso separado.

## 3. Riesgos funcionales

- Riesgo tributario: fallas de XML, firma, serie/correlativo o envio generan contingencia operativa.
- Riesgo sanitario: venta de vencidos o controlados sin receta expone a sanciones y dano al paciente.
- Riesgo de datos personales: recetas y clientes contienen datos sensibles.
- Riesgo de caja: ventas sin apertura/cierre impiden conciliacion.
- Riesgo de stock: editar stock directo rompe kardex y trazabilidad.

## 4. Criterios de aceptacion de Fase 1

- Alcance funcional definido por modulo.
- Reglas criticas identificadas.
- Arquitectura objetivo definida.
- Modelo de datos conceptual propuesto.
- Plan de desarrollo por fases definido.
- Fuentes regulatorias iniciales identificadas para validar la implementacion tributaria y sanitaria.
