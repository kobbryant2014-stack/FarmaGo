# FarmaGo - Fase 3: Seguridad, usuarios, roles y permisos

## 1. Objetivo

La Fase 3 establece la base de seguridad operativa de FarmaGo: roles profesionales para farmacia/botica peruana, permisos por modulo, control de cuenta activa/bloqueada, trazabilidad de accesos y auditoria inicial.

Esta fase no reemplaza las policies/controladores por modulo que se construiran en las siguientes fases; deja el marco de autorizacion listo para que cada modulo lo use.

## 2. Roles definidos

Roles operativos:

- `Administrador general`: control total del sistema.
- `Quimico farmaceutico`: validacion sanitaria, recetas, medicamentos controlados y reportes sanitarios.
- `Cajero`: POS, clientes, comprobantes basicos y caja.
- `Supervisor`: autorizaciones operativas, anulaciones, ajustes, caja y reportes.
- `Almacenero`: productos, lotes, inventario, compras y proveedores.
- `Contabilidad`: comprobantes, notas, caja, compras, cuentas por pagar y reportes contables.
- `Auditor`: acceso mayormente de lectura a reportes, auditoria y trazabilidad.
- `Empleado`: acceso minimo al dashboard.

Roles de compatibilidad:

- `Admin`: alias tecnico con todos los permisos para no romper instalaciones previas.
- `Inventario`: alias operativo compatible con `Almacenero`.

## 3. Permisos por modulo

Seeder actualizado: `database/seeders/RolePermissionSeeder.php`

Modulos cubiertos:

- Dashboard y alertas.
- Seguridad y usuarios.
- Configuracion.
- Productos.
- Inventario.
- Compras.
- Ventas POS.
- Facturacion electronica.
- Caja.
- Clientes.
- Recetas.
- Medicamentos controlados.
- Reportes.
- Auditoria.

Permisos criticos incorporados:

- `configurar sunat`
- `configurar series comprobantes`
- `editar precios productos`
- `seleccionar lote manual`
- `autorizar anulacion ventas`
- `emitir comprobantes`
- `emitir nota credito`
- `emitir nota debito`
- `descargar xml cdr pdf`
- `abrir caja`
- `cerrar caja`
- `validar recetas`
- `autorizar medicamentos controlados`
- `ver libro controlados`
- `ver auditoria`

## 4. Usuarios demo

Seeder actualizado: `database/seeders/UserSeeder.php`

Usuarios de ambiente local/semilla:

- `admin@farmago.com` / `admin123`
- `quimico@farmago.com` / `quimico123`
- `cajero@farmago.com` / `cajero123`
- `almacen@farmago.com` / `almacen123`
- `contabilidad@farmago.com` / `contabilidad123`
- `auditor@farmago.com` / `auditor123`
- `supervisor@farmago.com` / `supervisor123`

Importante: estas credenciales son solo de desarrollo o datos semilla. En produccion deben cambiarse o deshabilitarse.

## 5. Control de cuenta

Modelo actualizado: `app/Models/User.php`

Campos habilitados:

- `active`
- `sucursal_id`
- `last_login_at`
- `last_login_ip`
- `failed_login_attempts`
- `locked_until`

Reglas implementadas:

- Usuario inactivo no puede iniciar sesion.
- Usuario con `locked_until` futuro no puede iniciar sesion.
- Login fallido incrementa `failed_login_attempts`.
- Al quinto intento fallido, la cuenta queda bloqueada temporalmente por 15 minutos.
- Login exitoso limpia intentos fallidos y bloqueo.
- Login exitoso registra fecha, hora e IP de ultimo acceso.

## 6. Middleware de cuenta activa

Archivo: `app/Http/Middleware/EnsureUserAccountIsActive.php`

Comportamiento:

- Revisa usuarios autenticados en el grupo web.
- Si la cuenta fue desactivada o bloqueada durante una sesion, cierra la sesion.
- Redirige a login con mensaje funcional.
- Registra auditoria del cierre forzado.

Alias registrado:

- `active.user`

El middleware tambien fue agregado al grupo `web` para proteger sesiones activas.

## 7. Auditoria de seguridad

Servicio creado: `app/Services/AuditService.php`

Eventos iniciales registrados:

- `login_exitoso`
- `login_fallido`
- `login_fallido_usuario_no_encontrado`
- `login_fallido_usuario_bloqueado`
- `login_bloqueado_usuario_inactivo`
- `login_bloqueado_temporalmente`
- `logout`
- `usuario_registrado`
- `sesion_finalizada_por_cuenta_no_disponible`

La auditoria guarda:

- Usuario.
- Accion.
- Modulo.
- Entidad.
- Datos nuevos/anteriores cuando aplica.
- IP.
- User agent.
- Estado.
- Fecha/hora.

## 8. Rutas ajustadas

Archivo: `routes/web.php`

- Ruta `/admin` corregida para usar `role:Admin|Administrador general`.
- Se mantiene compatibilidad con el rol tecnico anterior `Admin`.

## 9. Pruebas agregadas

Archivo: `tests/Feature/Auth/SecurityAccessTest.php`

Casos cubiertos:

- Usuario inactivo no puede autenticarse y queda auditado.
- Usuario bloqueado temporalmente no puede autenticarse y queda auditado.
- Login exitoso actualiza metadata de acceso y registra auditoria.

## 10. Validacion ejecutada

Comandos:

```bash
php -l database\seeders\RolePermissionSeeder.php
php -l database\seeders\UserSeeder.php
php -l app\Models\User.php
php -l app\Services\AuditService.php
php -l app\Http\Middleware\EnsureUserAccountIsActive.php
php -l app\Http\Requests\Auth\LoginRequest.php
php -l app\Http\Controllers\Auth\AuthenticatedSessionController.php
php -l tests\Feature\Auth\SecurityAccessTest.php
APP_ENV=testing DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan migrate:fresh --seed --force
php artisan test
```

Resultado:

- Migracion y seed ejecutados correctamente.
- 99 permisos sincronizados.
- 10 roles sincronizados.
- Suite actual: 26 pruebas pasadas.
- Advertencias pendientes heredadas: `.env` ausente para algunas pruebas y esquema PHPUnit deprecado.

## 11. Pendientes para siguientes fases

- Construir CRUD administrativo de usuarios y roles.
- Aplicar permisos en controladores/rutas de productos, inventario, compras, ventas, caja y CPE.
- Implementar policies por entidad critica.
- Auditar cambios de rol, permisos, contrasenas y datos sensibles.
- Agregar cierre automatico por inactividad configurable.
- Agregar cifrado de credenciales SUNAT/OSE/PSE y certificados.
