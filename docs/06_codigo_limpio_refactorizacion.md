# Codigo limpio y refactorizacion

## Principios aplicados

- Separacion MVC propia de Laravel.
- Servicios para reglas de negocio complejas.
- Controladores delgados para coordinar peticiones y respuestas.
- FormRequest para validaciones.
- Scopes y metodos de modelo para reglas reutilizables.
- Nombres descriptivos en controladores, servicios y vistas.
- Layout administrativo reutilizable.

## Refactorizaciones realizadas

| Area | Problema detectado | Refactorizacion aplicada |
| --- | --- | --- |
| Totales de venta | Riesgo de duplicar importes cuando FEFO divide por lote. | Creacion de `VentaCalculatorService`. |
| VentaService | Calculo mezclado con registro de detalle. | Separacion entre calculo, asignacion de lote y registro de salida. |
| Kardex | Kardex por producto confundia trazabilidad por lote. | Metodos `kardexPorProducto()` y `kardexPorLote()`. |
| Stock bajo | No usaba la misma regla de stock disponible. | Scopes y metodos `stockDisponible()` y `estaEnStockBajo()`. |
| Vistas | Existian vistas que apuntaban a `layouts.admin` inexistente. | Creacion de layout administrativo unico. |
| Validacion | Validaciones dispersas o faltantes en formularios nuevos. | Creacion de FormRequest por modulo. |
| Comentarios | Comentarios con caracteres danados en modelos. | Limpieza de `Compra` y `Venta`. |

## Codigo limpio actual

Nivel estimado: Medio alto.

El sistema usa buenas practicas Laravel y servicios para reglas relevantes. Aun puede mejorar con pruebas de feature para todos los CRUDs, politicas de autorizacion por modelo y enums formales para estados.

## Mejoras recomendadas

1. Crear enums para estados de lote, venta, compra y tipos de comprobante.
2. Agregar policies por modelo.
3. Aumentar cobertura de pruebas de controladores.
4. Crear componentes Blade reutilizables para tablas y formularios.
5. Agregar auditoria visual de acciones criticas.
