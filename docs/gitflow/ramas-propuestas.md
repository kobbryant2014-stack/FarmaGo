# Ramas propuestas

La siguiente tabla define las ramas recomendadas para evidenciar GitFlow en FarmaGo. Las ramas deben crearse cuando exista trabajo real para desarrollar, revisar o preparar una version.

| Rama | Proposito | Modulo asociado | Destino de Pull Request |
| --- | --- | --- | --- |
| `main` | Mantener la version estable y entregable del proyecto. | Proyecto completo | No aplica |
| `develop` | Integrar funcionalidades antes de liberar una version estable. | Proyecto completo | `main` mediante `release/*` |
| `feature/dashboard` | Mejorar panel principal, metricas y accesos rapidos. | Dashboard | `develop` |
| `feature/productos` | Gestionar productos, categorias y datos farmaceuticos. | Productos | `develop` |
| `feature/inventario-lotes` | Controlar lotes, stock, vencimientos y Kardex. | Inventario | `develop` |
| `feature/ventas-fefo` | Implementar ventas con salida FEFO y validacion de stock. | Ventas | `develop` |
| `feature/compras` | Registrar compras y actualizar existencias. | Compras | `develop` |
| `feature/facturacion-electronica` | Preparar comprobantes y datos de facturacion. | Facturacion | `develop` |
| `feature/roles-permisos` | Administrar usuarios, roles y permisos. | Seguridad | `develop` |
| `feature/reportes` | Generar reportes de ventas, inventario y gestion. | Reportes | `develop` |
| `feature/auditoria` | Documentar trazabilidad de acciones importantes. | Auditoria | `develop` |
| `release/v1.0.0` | Validar la version academica, ajustar documentacion y preparar tag. | Release academico | `main` y luego `develop` |
| `hotfix/correccion-readme` | Corregir errores urgentes de documentacion publicados en `main`. | Documentacion | `main` y luego `develop` |

## Recomendacion de uso

1. Crear `develop` desde `main`.
2. Crear cada `feature/*` desde `develop`.
3. Abrir Pull Request de cada `feature/*` hacia `develop`.
4. Crear `release/v1.0.0` desde `develop` cuando el sistema este listo.
5. Abrir Pull Request de `release/v1.0.0` hacia `main`.
6. Crear el tag `v1.0.0` desde `main`.
7. Usar `hotfix/*` solo para correcciones urgentes sobre version estable.
