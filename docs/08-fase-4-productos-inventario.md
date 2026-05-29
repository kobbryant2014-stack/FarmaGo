# FarmaGo - Fase 4: Productos e inventario

## 1. Objetivo

La Fase 4 implementa la base backend del inventario farmaceutico: modelos Eloquent, relaciones, stock por lote/almacen, reglas FEFO, bloqueo de lotes vencidos/inmovilizados y kardex. No incluye todavia pantallas CRUD; esas vistas deben apoyarse sobre esta capa.

## 2. Modelos creados

Modelos core y catalogos:

- `Empresa`
- `Sucursal`
- `Almacen`
- `Laboratorio`
- `PrincipioActivo`
- `PresentacionProducto`

Modelos reforzados:

- `Producto`
- `Lote`
- `MovimientoInventario`
- `DetalleVenta`
- `DetalleCompra`

## 3. Producto

`Producto` ahora soporta los campos farmaceuticos y tributarios de la Fase 2:

- Codigo interno.
- Codigo de barras.
- DCI.
- Principio activo.
- Concentracion.
- Forma farmaceutica.
- Presentacion.
- Laboratorio.
- Registro sanitario.
- Condicion de venta.
- Precios por unidad, caja y blister.
- Afectacion tributaria e IGV.
- Requiere receta.
- Requiere receta retenida.
- Medicamento controlado.
- Cadena de frio.
- Ubicacion en almacen.
- Estado operativo/sanitario.

Relaciones:

- Categoria.
- Laboratorio.
- Principio activo.
- Presentacion.
- Lotes.
- Movimientos de inventario.

Scopes y metodos:

- `vendibles()`
- `conReceta()`
- `bajoStock()`
- `busquedaPos($termino)`
- `stock_total`
- `stock_disponible`
- `tieneStock($cantidad)`
- `estaDisponibleParaVenta()`

## 4. Lotes

`Lote` ahora soporta:

- Almacen.
- Fecha de fabricacion.
- Fecha de vencimiento.
- Estado: activo, vencido, inmovilizado, retirado, agotado.
- Motivo de bloqueo.
- Stock actual calculado.

Reglas:

- Un lote vencido no es disponible para venta.
- Un lote inmovilizado o retirado no es disponible para venta.
- Un lote inactivo no es disponible para venta.
- La salida FEFO ordena por fecha de vencimiento ascendente.

## 5. Calculo de stock

Se corrigio una regla critica: el stock no debe duplicarse cuando una compra crea un lote con `stock_inicial` y registra un movimiento de entrada por trazabilidad.

Formula aplicada:

```text
stock_actual = stock_inicial + suma(movimientos no duplicativos)
```

La entrada de compra (`tipo = entrada`, `origen = compra`) queda como evidencia de kardex, pero no suma dos veces porque `stock_inicial` ya representa el ingreso inicial del lote.

Ejemplo:

```text
stock_inicial = 10
entrada por compra = +10
stock_actual = 10
venta = -3
stock_actual = 7
```

## 6. Servicio FEFO

Servicio creado: `App\Services\FefoStockService`

Responsabilidades:

- Validar producto disponible.
- Validar lote activo, no vencido, no inmovilizado y con stock.
- Seleccionar lotes por FEFO.
- Dividir una salida en varios lotes si el primer lote no cubre toda la cantidad.
- Filtrar por almacen cuando se indique `almacen_id`.

Metodo principal:

```php
seleccionarLotesParaSalida(int $productoId, float $cantidad, ?int $almacenId = null)
```

Retorna una coleccion de asignaciones:

```php
[
    ['lote' => $lote, 'cantidad' => 5],
    ['lote' => $otroLote, 'cantidad' => 3],
]
```

## 7. InventarioService

`InventarioService` fue reorganizado para usar el modelo reforzado:

- Ajuste manual con motivo obligatorio.
- Registro en `movimientos_inventario`.
- Registro auxiliar en `ajustes_inventario` cuando el lote tiene almacen.
- Auditoria del ajuste.
- Productos con stock bajo.
- Lotes por vencer.
- Lotes vencidos con stock.
- Kardex por producto.
- Kardex por lote.
- Resumen por categoria.
- Bloqueo de lotes vencidos.
- Valorizacion de inventario.

## 8. VentaService

`VentaService` ahora puede trabajar de dos formas:

- Venta con `lote_id` manual, validada por permisos en fases siguientes.
- Venta solo con `producto_id`, donde el sistema selecciona lotes automaticamente por FEFO.

En ambos casos registra:

- Detalle de venta.
- Movimiento de salida.
- Almacen asociado al lote.
- Estado valido del movimiento.

Tambien se amplio busqueda POS por:

- Nombre.
- Codigo de barras.
- Codigo interno.
- DCI.
- Principio activo.
- Registro sanitario.

## 9. Reglas implementadas

- No seleccionar lotes vencidos para venta.
- No seleccionar lotes inmovilizados.
- No seleccionar lotes retirados.
- No vender productos inactivos o con estado distinto de activo.
- No permitir salida sin stock suficiente.
- Aplicar FEFO.
- Mantener kardex trazable por movimiento.
- Evitar duplicidad de stock por entrada de compra.
- Bloquear lotes vencidos sin eliminarlos.

## 10. Pruebas agregadas

Archivo: `tests/Feature/Inventory/FefoStockServiceTest.php`

Casos:

- Seleccion de lotes por FEFO.
- Omision de lotes vencidos e inmovilizados.
- Error cuando el stock disponible no alcanza.
- La entrada de compra no duplica el stock del lote.

## 11. Validacion ejecutada

Comandos:

```bash
php -l app\Models\Producto.php
php -l app\Models\Lote.php
php -l app\Models\MovimientoInventario.php
php -l app\Services\FefoStockService.php
php -l app\Services\InventarioService.php
php -l app\Services\VentaService.php
php artisan test tests\Feature\Inventory\FefoStockServiceTest.php
php artisan test
APP_ENV=testing DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan migrate:fresh --seed --force
```

Resultado:

- Suite actual: 29 pruebas pasadas.
- Migracion fresca con seeders: correcta.
- Advertencias heredadas: `.env` ausente y esquema PHPUnit deprecado.

## 12. Pendientes de la Fase 4 visual/API

- CRUD de productos.
- CRUD de laboratorios, principios activos y presentaciones.
- Registro visual de lotes.
- Pantalla de kardex.
- Pantalla de stock critico.
- Pantalla de vencimientos a 30, 60 y 90 dias.
- Politicas de permisos para lote manual, ajustes e inmovilizaciones.
- Migracion futura para cantidades decimales si se habilita fraccionamiento real por blister/unidad.
