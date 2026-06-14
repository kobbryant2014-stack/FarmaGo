# Commits convencionales

FarmaGo usa commits convencionales para que el historial sea claro, revisable y facil de relacionar con los modulos del sistema.

## Formato

```text
tipo: descripcion breve en infinitivo o presente
```

La descripcion debe ser corta, especifica y escrita en minusculas cuando sea posible.

## Tipos permitidos

| Tipo | Uso |
| --- | --- |
| `feat:` | Nueva funcionalidad del sistema. |
| `fix:` | Correccion de errores. |
| `docs:` | Cambios de documentacion. |
| `test:` | Pruebas nuevas o ajustes de pruebas. |
| `refactor:` | Mejora interna sin cambiar comportamiento esperado. |
| `chore:` | Tareas de mantenimiento, configuracion o limpieza. |
| `ci:` | Cambios en GitHub Actions u otra automatizacion. |

## Ejemplos aplicados a FarmaGo

```text
feat: agregar control de lotes para productos
feat: implementar venta con salida fefo
fix: corregir calculo de stock disponible
fix: validar permisos al registrar compras
docs: documentar flujo gitflow academico
docs: actualizar instrucciones de ejecucion local
test: agregar prueba de venta con inventario insuficiente
test: cubrir migracion de lotes vencidos
refactor: mover calculo de ventas a servicio dedicado
refactor: simplificar controlador de productos
chore: actualizar dependencias npm
chore: ordenar archivos de documentacion
ci: agregar workflow laravel ci
ci: ejecutar pruebas en pull requests hacia develop
```

## Buenas practicas

- Hacer commits pequenos y frecuentes.
- Evitar mensajes genericos como `cambios`, `update` o `arreglo final`.
- Relacionar el commit con el modulo afectado.
- No incluir `.env`, credenciales, claves privadas, `vendor` ni `node_modules`.
- Ejecutar pruebas antes de subir la rama cuando el cambio afecte codigo Laravel.
