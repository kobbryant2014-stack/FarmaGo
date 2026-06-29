# Uso de ORM en FarmaGo

## Modelos utilizados

FarmaGo usa Laravel Eloquent como ORM principal. Los modelos relevantes para PA3 son:

- `Producto`: representa medicamentos y productos farmaceuticos.
- `Categoria`: clasifica productos.
- `Lote`: controla vencimiento, estado y stock por lote.
- `MovimientoInventario`: registra entradas, salidas y ajustes.
- `Cliente`: representa compradores.
- `Venta`: cabecera de venta.
- `DetalleVenta`: lineas de venta.
- `User`: usuario responsable de operaciones.

## Relaciones implementadas

- `Producto belongsTo Categoria`.
- `Producto hasMany Lote`.
- `Producto hasMany MovimientoInventario`.
- `Venta belongsTo Cliente`.
- `Venta belongsTo User` mediante `usuario`.
- `Venta hasMany DetalleVenta`.
- `DetalleVenta belongsTo Producto`.
- `DetalleVenta belongsTo Lote`.
- `Lote belongsTo Producto`.

## Consultas optimizadas

En las pruebas se usa `Venta::with(['cliente', 'usuario', 'detalles.producto', 'detalles.lote'])` para cargar relaciones en una consulta controlada y evitar consultas repetitivas tipo N+1.

Tambien se validan scopes de dominio:

- `Producto::vendibles()`
- `Producto::conReceta()`
- `Producto::bajoStock()`
- `Venta::completadas()`
- `Venta::anuladas()`
- `Venta::activas()`

## Beneficios del ORM

Eloquent permite expresar reglas de persistencia con modelos de dominio claros, relaciones navegables, scopes reutilizables y pruebas con SQLite en memoria. Esto reduce SQL repetitivo, mejora la mantenibilidad y facilita pruebas automatizadas.

## Evidencias de pruebas

Archivo: `tests/Feature/Orm/FarmaGoOrmTest.php`.

Escenarios cubiertos:

- Creacion, lectura, actualizacion y eliminacion logica de productos.
- Relaciones entre venta, cliente, usuario, detalle, producto y lote.
- Consultas filtradas de productos vendibles, con receta y bajo stock.
- Scopes de ventas completadas, anuladas y activas.

