# Informe tecnico - FarmaGo

## 1. Titulo

FarmaGo - Sistema de Gestion Farmaceutica Inteligente.

## 2. Integrantes

- Product Owner: Integrante 1.
- Scrum Master: Integrante 2.
- Developer: Integrante 3.

## 3. Introduccion

FarmaGo es una aplicacion web para apoyar la administracion de una farmacia, controlando productos, lotes, compras, ventas, Kardex, usuarios y reportes.

## 4. Descripcion del problema

Las farmacias pequenas suelen manejar stock, vencimientos y ventas con procesos manuales, lo que genera errores de inventario, productos vencidos y poca visibilidad del negocio.

## 5. Solucion propuesta

Una aplicacion Laravel con autenticacion, roles, inventario por lotes, FEFO, Kardex, comprobantes simples y reportes administrativos.

## 6. Objetivos

- Controlar inventario farmaceutico por lote.
- Registrar compras y ventas.
- Aplicar FEFO para salida de productos.
- Alertar stock bajo y vencimientos.
- Generar reportes basicos.
- Documentar metodologia agil e IA generativa.

## 7. Tecnologias utilizadas

PHP 8.1+, Laravel 10, Blade, AdminLTE, Tailwind/Breeze, Vite, MySQL, SQLite testing, Spatie Laravel Permission y PHPUnit.

## 8. Arquitectura del sistema

El sistema usa MVC de Laravel, Eloquent ORM, servicios de dominio para reglas complejas, FormRequest para validacion y Blade para vistas.

## 9. Metodologia agil aplicada

Se aplico Scrum con sprint de una semana, backlog priorizado, sprint goal, sprint review y retrospectiva.

## 10. Sprint desarrollado

Sprint 1: adecuacion funcional del sistema para conectar backend, interfaz, reglas criticas y documentacion.

## 11. Uso de IA generativa

Codex en Visual Studio Code apoyo el analisis, refactorizacion, generacion de vistas base, validaciones, documentacion y pruebas. El equipo reviso y valido los resultados.

## 12. Modulos implementados

Login, dashboard, usuarios, productos, lotes, clientes, proveedores, compras, ventas, comprobantes, Kardex y reportes.

## 13. Base de datos

La base de datos usa migraciones Laravel para usuarios, roles, productos, clientes, proveedores, lotes, compras, ventas, detalle de compras, detalle de ventas y movimientos de inventario.

## 14. Manejo de excepciones

Se aplican FormRequest, transacciones, `try/catch`, `report($e)` y mensajes amigables para operaciones criticas.

## 15. Codigo limpio

Se separo la logica de negocio en servicios y se redujo la responsabilidad de controladores. El calculo de ventas se centralizo.

## 16. Refactorizacion

Se corrigio calculo de totales, Kardex, stock bajo, layout faltante y registro publico abierto.

## 17. Pruebas funcionales

Se documentaron casos funcionales y se mantienen pruebas automatizadas con PHPUnit para autenticacion, seguridad e inventario FEFO.

## 18. Resultados obtenidos

El sistema queda listo para demostracion academica con modulos conectados, reglas de negocio corregidas y evidencias de proceso.

## 19. Conclusiones

FarmaGo aprovecha una base backend robusta y ahora presenta una interfaz funcional que permite demostrar los flujos principales de una farmacia.

## 20. Recomendaciones

Agregar exportacion de reportes, pruebas end-to-end, politicas por modelo y mejoras visuales especificas para pantallas moviles.
