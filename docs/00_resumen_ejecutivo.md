# Resumen ejecutivo - FarmaGo

## Estado actual del sistema

FarmaGo es una aplicacion web Laravel 10 para gestion farmaceutica. La adecuacion conecta el backend existente con una interfaz administrativa funcional y deja evidencia academica para la rubrica "Desarrollo Agil de Aplicacion con Inteligencia Artificial Generativa".

## Tecnologias utilizadas

- PHP 8.1+
- Laravel 10
- Laravel Breeze para autenticacion
- Spatie Laravel Permission para roles y permisos
- Blade con layout administrativo basado en AdminLTE
- Vite, Tailwind y Alpine como stack frontend base
- MySQL para ejecucion local
- SQLite para pruebas automatizadas
- PHPUnit y Laravel Pint

## Modulos implementados

- Login y cierre de sesion.
- Registro publico cerrado.
- Dashboard con metricas reales.
- CRUD de productos.
- CRUD de lotes.
- CRUD de clientes.
- CRUD de proveedores.
- Registro y consulta de compras.
- Registro y consulta de ventas.
- Comprobante simple imprimible.
- Kardex por producto y por lote.
- Reportes basicos.
- Gestion basica de usuarios protegida por rol administrador.

## Correcciones criticas aplicadas

- Calculo centralizado de subtotal, descuento, base imponible, IGV y total de venta.
- Evita duplicidad de totales cuando FEFO divide una venta en varios lotes.
- Kardex por producto separado del Kardex por lote.
- Stock disponible basado en lotes activos, no inmovilizados y no vencidos.
- Alertas de stock bajo usando la misma regla de stock disponible.

## Nivel frente a la rubrica

El sistema queda en condicion demostrable para aspirar a nivel sobresaliente si se acompana con repositorio Git, exposicion del flujo Scrum, evidencias de uso de IA, pruebas ejecutadas y video explicativo.

## Proximos pasos

1. Ejecutar migraciones y seeders en ambiente local.
2. Validar los flujos principales con usuarios de prueba.
3. Grabar el video explicativo siguiendo el guion.
4. Subir la rama `adecuacion-rubrica` al repositorio remoto.
