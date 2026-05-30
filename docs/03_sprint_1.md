# Sprint 1: Adecuacion funcional de FarmaGo

## Duracion

1 semana.

## Objetivo

Conectar los modulos principales del backend con una interfaz funcional y corregir reglas criticas de inventario y ventas.

## Alcance

- Dashboard funcional.
- CRUD de productos.
- CRUD de clientes.
- CRUD de proveedores.
- Gestion de lotes.
- Registro de compras.
- Registro de ventas.
- Comprobante simple.
- Reportes basicos.
- Kardex.
- Manejo de excepciones.
- Documentacion academica.

## Entregables

| Entregable | Estado | Evidencia |
| --- | --- | --- |
| Dashboard funcional | Completado | `resources/views/dashboard.blade.php` |
| CRUD de productos | Completado | `ProductoController` y vistas `productos` |
| CRUD de clientes | Completado | `ClienteController` y vistas `clientes` |
| CRUD de proveedores | Completado | `ProveedorController` y vistas `proveedores` |
| Registro de ventas | Completado | `VentaController`, `VentaService` |
| Comprobante simple | Completado | `ventas/comprobante.blade.php` |
| Reportes basicos | Completado | `ReporteController` |
| Kardex | Completado | `KardexController`, `InventarioService` |
| Manejo de excepciones | Completado | Controladores y servicios con `try/catch` y transacciones |
| Documentacion academica | Completado | Carpeta `docs/` |

## Definition of Done

- Las rutas cargan sin error.
- Las vistas Blade compilan.
- Las pruebas automatizadas pasan.
- El README refleja funcionalidades reales.
- Las evidencias de Scrum, IA, excepciones y pruebas estan documentadas.
