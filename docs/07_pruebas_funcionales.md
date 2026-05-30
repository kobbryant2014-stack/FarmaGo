# Pruebas funcionales

| Codigo | Caso de prueba | Datos usados | Resultado esperado | Estado |
| --- | --- | --- | --- | --- |
| CP01 | Inicio de sesion correcto | Usuario administrador seed | Ingresa al dashboard. | Pendiente de ejecucion manual |
| CP02 | Inicio de sesion incorrecto | Password incorrecto | Muestra error y no autentica. | Automatizado |
| CP03 | Registro de producto valido | Codigo unico, nombre, precio, categoria | Producto creado. | Pendiente de ejecucion manual |
| CP04 | Registro de producto invalido | Precio negativo | Muestra validacion. | Pendiente de ejecucion manual |
| CP05 | Registro de cliente valido | Documento unico y nombres | Cliente creado. | Pendiente de ejecucion manual |
| CP06 | Registro de proveedor valido | RUC unico y razon social | Proveedor creado. | Pendiente de ejecucion manual |
| CP07 | Registro de lote valido | Producto, proveedor, lote, vencimiento y cantidad | Lote creado y disponible. | Pendiente de ejecucion manual |
| CP08 | Venta con stock suficiente | Producto con lote disponible | Venta registrada, stock descontado. | Pendiente de ejecucion manual |
| CP09 | Venta con stock insuficiente | Cantidad mayor al stock | Muestra error y no descuenta stock. | Automatizado en servicio FEFO |
| CP10 | Comprobante de venta generado | Venta registrada | Comprobante simple imprimible. | Pendiente de ejecucion manual |
| CP11 | Reporte de stock bajo | Producto bajo minimo | Producto aparece en reporte. | Pendiente de ejecucion manual |
| CP12 | Reporte de productos proximos a vencer | Lote activo proximo a vencer | Lote aparece en reporte. | Pendiente de ejecucion manual |
| CP13 | Consulta de Kardex | Producto con movimientos | Muestra entradas, salidas y saldo. | Pendiente de ejecucion manual |
| CP14 | Usuario sin permiso intenta acceder | Usuario no administrador | Acceso a usuarios bloqueado. | Pendiente de ejecucion manual |

## Pruebas automatizadas

Comando:

```bash
php artisan test
```

Resultado esperado:

- Pruebas de autenticacion.
- Pruebas de seguridad de usuarios inactivos y bloqueados.
- Pruebas de FEFO e inventario.
- Pruebas de perfil.
