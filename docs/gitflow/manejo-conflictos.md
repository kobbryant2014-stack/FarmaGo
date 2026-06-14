# Manejo de conflictos

Un conflicto ocurre cuando dos ramas modifican las mismas lineas de un archivo y Git no puede decidir automaticamente que version conservar. En FarmaGo puede pasar, por ejemplo, si dos integrantes editan `README.md`, la documentacion de inventario o la documentacion de ventas al mismo tiempo.

## Escenario realista

- La rama `feature/inventario-lotes` actualiza la documentacion de inventario para explicar lotes, vencimientos y Kardex.
- La rama `feature/ventas-fefo` actualiza la misma seccion para explicar como las ventas descuentan stock usando FEFO.
- Ambas ramas modifican lineas cercanas del mismo archivo.
- Al intentar fusionar `develop` dentro de `feature/ventas-fefo`, Git marca conflicto.

## Pasos para resolver

```bash
git checkout develop
git pull origin develop
git checkout feature/ventas-fefo
git merge develop
```

Luego se deben revisar los archivos en conflicto. Git marcara zonas similares a:

```text
<<<<<<< HEAD
Contenido de feature/ventas-fefo
=======
Contenido de develop
>>>>>>> develop
```

El equipo debe editar manualmente el archivo, conservar una version coherente que integre ambos cambios y eliminar los marcadores `<<<<<<<`, `=======` y `>>>>>>>`.

Despues de resolver:

```bash
git add .
git commit -m "fix: resolver conflicto entre ventas e inventario"
git push origin feature/ventas-fefo
```

## Buenas practicas

- Actualizar la rama con `develop` antes de abrir el Pull Request.
- Resolver conflictos localmente y ejecutar pruebas.
- Pedir revision a otro integrante antes de fusionar.
- Evitar mezclar cambios de muchos modulos en una sola rama.

## Evidencia esperada

Para el informe academico se debe adjuntar una captura del conflicto resuelto o del commit de resolucion. Tambien puede incluirse una captura del Pull Request donde GitHub muestre que no quedan conflictos y que el workflow de CI finalizo correctamente.
