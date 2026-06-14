# Pull Requests

Un Pull Request es una solicitud de integracion de cambios entre ramas de GitHub. En FarmaGo se usa para revisar codigo, documentar la funcionalidad desarrollada, ejecutar GitHub Actions y dejar evidencia de colaboracion antes de fusionar cambios.

## Flujo correcto

```text
rama local -> commit -> push -> Pull Request -> revision -> merge
```

El flujo recomendado es:

1. Crear una rama local desde `develop` o `main`.
2. Realizar cambios en el modulo correspondiente.
3. Registrar commits convencionales.
4. Subir la rama con `git push`.
5. Crear el Pull Request en GitHub.
6. Esperar revision del equipo y ejecucion del workflow.
7. Fusionar el Pull Request cuando no existan conflictos y las pruebas pasen.

## Plantilla de descripcion

```markdown
## Descripcion

Resumen breve del cambio.

## Rama origen

feature/nombre-de-rama

## Rama destino

develop

## Cambios realizados

- Cambio 1.
- Cambio 2.

## Modulo afectado

Productos, inventario, ventas, compras, facturacion, reportes, auditoria u otro.

## Pruebas ejecutadas

- composer install
- npm install
- npm run build
- php artisan test

## Evidencia

Capturas, logs o enlaces a ejecuciones de GitHub Actions.

## Riesgos

Impacto esperado y puntos que requieren revision.
```

## Ejemplo: `feature/ventas-fefo` hacia `develop`

```markdown
## Descripcion

Implementa validaciones de venta usando salida FEFO para descontar primero los lotes con fecha de vencimiento mas cercana.

## Rama origen

feature/ventas-fefo

## Rama destino

develop

## Cambios realizados

- Ajuste del servicio de ventas.
- Validacion de stock disponible por lote.
- Registro de movimiento en Kardex.
- Pruebas del flujo de venta.

## Modulo afectado

Ventas e inventario.

## Pruebas ejecutadas

- npm run build
- php artisan test

## Evidencia

Captura del PR, historial de commits y GitHub Actions ejecutado.

## Riesgos

Debe revisarse que compras e inventario sigan actualizando stock correctamente.
```

## Ejemplo: `release/v1.0.0` hacia `main`

```markdown
## Descripcion

Prepara la version academica 1.0.0 de FarmaGo con documentacion GitFlow, workflow Laravel CI y evidencias requeridas.

## Rama origen

release/v1.0.0

## Rama destino

main

## Cambios realizados

- Documentacion academica de GitFlow.
- Plantilla de Pull Request.
- Workflow Laravel CI.
- Changelog de version academica.

## Modulo afectado

Proyecto completo y documentacion.

## Pruebas ejecutadas

- composer install
- npm install
- npm run build
- php artisan test

## Evidencia

Capturas del workflow ejecutado, PR aprobado y tag `v1.0.0`.

## Riesgos

Verificar que no se incluya `.env` ni archivos generados.
```

## Checklist de revision

- [ ] El sistema compila correctamente.
- [ ] Las migraciones funcionan.
- [ ] Las pruebas se ejecutan.
- [ ] No se sube `.env`.
- [ ] No se rompe ventas.
- [ ] No se rompe inventario.
- [ ] Se revisan permisos.
- [ ] Se actualiza documentacion si corresponde.
