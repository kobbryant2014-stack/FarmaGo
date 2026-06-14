# GitFlow en FarmaGo

GitFlow es un modelo de trabajo con Git que organiza el desarrollo mediante ramas con responsabilidades claras. Su objetivo es separar la version estable, la integracion de cambios, las nuevas funcionalidades, las versiones candidatas y las correcciones urgentes.

FarmaGo aplica GitFlow para evidenciar el uso correcto de Git y GitHub en la evaluacion academica. Cada integrante puede trabajar en una rama especifica, registrar commits convencionales, abrir Pull Requests y validar los cambios con CI/CD antes de fusionarlos.

## Rama `main`

La rama `main` representa la version estable de FarmaGo. Debe contener codigo listo para entrega academica o despliegue. No se debe trabajar directamente sobre esta rama; los cambios llegan mediante Pull Requests desde `release/*` o `hotfix/*`.

## Rama `develop`

La rama `develop` funciona como rama de integracion. Recibe cambios terminados desde ramas `feature/*` y permite validar que los modulos trabajen correctamente antes de preparar una version estable.

## Ramas `feature/*`

Las ramas `feature/*` se usan para desarrollar nuevos modulos o mejoras especificas. En FarmaGo pueden representar funcionalidades como productos, inventario, ventas, compras, facturacion, usuarios, reportes y auditoria. Cada rama `feature/*` nace desde `develop` y vuelve a `develop` mediante Pull Request.

## Ramas `release/*`

Las ramas `release/*` se usan para preparar versiones. En FarmaGo, `release/v1.0.0` permite validar documentacion, pruebas, configuracion CI/CD y ultimos ajustes antes de integrar la version academica en `main`.

## Ramas `hotfix/*`

Las ramas `hotfix/*` se usan para correcciones urgentes sobre la version estable. Por ejemplo, `hotfix/correccion-readme` puede corregir una instruccion critica del README publicada en `main`. Luego el cambio debe integrarse tambien a `develop`.

## Pull Requests

FarmaGo usa Pull Requests para integrar cambios de forma controlada. El flujo esperado es:

1. Crear una rama desde `develop` o `main`, segun corresponda.
2. Realizar commits convencionales.
3. Subir la rama a GitHub.
4. Abrir un Pull Request hacia la rama destino.
5. Revisar cambios, pruebas, riesgos y evidencias.
6. Fusionar solo cuando el Pull Request este aprobado y CI/CD finalice correctamente.

## Relacion con los modulos FarmaGo

- `feature/dashboard`: panel principal y metricas del sistema.
- `feature/productos`: catalogo de productos farmaceuticos.
- `feature/inventario-lotes`: lotes, stock, vencimientos y Kardex.
- `feature/ventas-fefo`: ventas con salida FEFO.
- `feature/compras`: registro de compras y actualizacion de existencias.
- `feature/facturacion-electronica`: comprobantes y datos tributarios.
- `feature/roles-permisos`: usuarios, roles y permisos.
- `feature/reportes`: reportes de ventas, inventario y gestion.
- `feature/auditoria`: trazabilidad de acciones importantes.

Este flujo deja evidencia revisable de colaboracion, organizacion de ramas, Pull Requests, automatizacion y buenas practicas de gestion de codigo.
