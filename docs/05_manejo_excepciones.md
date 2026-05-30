# Manejo de excepciones

## Estrategia general

FarmaGo aplica manejo de excepciones en operaciones criticas mediante:

- Validaciones con FormRequest.
- `try/catch` en controladores.
- `report($e)` para registrar errores.
- Mensajes amigables con `withErrors()` y `withInput()`.
- Transacciones con `DB::transaction()` en ventas, compras e inventario.

## Autenticacion y permisos

- Laravel Breeze controla login, logout y recuperacion de contrasena.
- Spatie Permission protege la gestion de usuarios.
- El registro publico fue cerrado.
- Usuarios sin permisos son bloqueados por middleware.

## Productos

Errores controlados:

- Codigo duplicado.
- Nombre obligatorio.
- Precio negativo.
- Stock minimo negativo.
- Error al guardar, actualizar o desactivar.

## Ventas

Errores controlados:

- Venta sin detalles.
- Cantidad negativa o cero.
- Precio negativo.
- Stock insuficiente.
- Lote vencido o no disponible.
- Error al calcular totales.

La venta se ejecuta en transaccion para evitar ventas parciales y movimientos de inventario inconsistentes.

## Compras

Errores controlados:

- Proveedor obligatorio.
- Producto inexistente.
- Numero de lote obligatorio.
- Fecha de vencimiento invalida.
- Cantidad o precio invalidos.

La compra se ejecuta en transaccion y registra entrada de inventario.

## Stock e inventario

El stock disponible considera lotes activos, no inmovilizados, no vencidos y con movimientos validos. El Kardex separa consultas por producto y por lote para evitar saldos confusos.

## Mejoras futuras

- Personalizar vistas de error 403, 404 y 500.
- Agregar pruebas automatizadas por cada controlador nuevo.
- Registrar eventos de auditoria por cada operacion critica.
