# Pull Requests

Los Pull Requests son la evidencia principal de colaboracion en GitHub. En FarmaGo se usan para revisar cambios, ejecutar CI/CD y documentar decisiones antes de fusionar ramas.

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

- [ ] Compilacion correcta.
- [ ] Migraciones correctas.
- [ ] Pruebas ejecutadas.
- [ ] No se sube `.env`.
- [ ] No se rompe inventario.
- [ ] No se rompe ventas.
- [ ] Permisos revisados.
