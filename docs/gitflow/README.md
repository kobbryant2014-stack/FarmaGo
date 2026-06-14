# GitFlow en FarmaGo

FarmaGo adopta GitFlow como flujo de trabajo academico para ordenar el desarrollo colaborativo, separar codigo estable de codigo en construccion y dejar evidencia verificable en GitHub mediante ramas, commits y Pull Requests.

## Ramas principales

| Rama | Uso en FarmaGo | Regla de integracion |
| --- | --- | --- |
| `main` | Contiene versiones estables del sistema FarmaGo listas para entrega academica o despliegue. | Solo recibe cambios desde `release/*` o `hotfix/*` mediante Pull Request aprobado. |
| `develop` | Integra funcionalidades terminadas antes de preparar una version estable. | Recibe Pull Requests desde `feature/*`. |
| `feature/*` | Implementa modulos o mejoras especificas: productos, inventario, ventas, compras, facturacion, reportes o auditoria. | Sale desde `develop` y vuelve a `develop` por Pull Request. |
| `release/*` | Prepara una version candidata, corrige documentacion final y valida pruebas antes de publicar. | Sale desde `develop` y se integra a `main` y `develop`. |
| `hotfix/*` | Corrige errores urgentes detectados en `main`, por ejemplo documentacion critica o fallos de configuracion. | Sale desde `main` y se integra a `main` y `develop`. |

## Integracion mediante Pull Requests

Todo cambio debe integrarse mediante Pull Request para conservar trazabilidad. Cada PR debe indicar rama origen, rama destino, modulo afectado, cambios realizados, pruebas ejecutadas, evidencia y riesgos. La plantilla ubicada en `.github/pull_request_template.md` estandariza esta informacion.

Antes de fusionar un PR se debe revisar:

- Que el codigo compile correctamente.
- Que las migraciones funcionen.
- Que las pruebas automatizadas pasen.
- Que no se suba `.env` ni archivos sensibles.
- Que no se rompan los flujos de inventario, ventas ni permisos.

## Relacion con los modulos FarmaGo

GitFlow permite dividir el trabajo del equipo por modulo:

- `feature/productos`: mantenimiento de productos farmaceuticos.
- `feature/inventario-lotes`: control de lotes, stock y vencimientos.
- `feature/ventas-fefo`: ventas aplicando salida FEFO.
- `feature/compras`: registro de compras y actualizacion de inventario.
- `feature/facturacion-electronica`: comprobantes y datos tributarios.
- `feature/roles-permisos`: usuarios, roles y autorizaciones.
- `feature/reportes`: reportes operativos y administrativos.
- `feature/auditoria`: trazabilidad de operaciones y cambios.

Este flujo ayuda a que cada integrante trabaje en una rama aislada, solicite revision por Pull Request y deje historial de commits entendible para la evaluacion.
