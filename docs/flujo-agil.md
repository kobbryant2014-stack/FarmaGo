# Flujo agil e integracion de pruebas

## Roles del equipo

- Product Owner: prioriza requisitos funcionales y academicos.
- Scrum Master: facilita el cumplimiento del sprint y elimina bloqueos.
- Lider tecnico: revisa arquitectura, TDD, ORM y calidad del codigo.
- Desarrolladores: implementan funcionalidades, pruebas y documentacion.
- QA/Tester: valida escenarios correctos, limites e invalidos.

## Flujo de trabajo

1. Seleccionar una historia de usuario del backlog.
2. Crear una rama de trabajo.
3. Escribir primero pruebas automatizadas.
4. Ejecutar pruebas y confirmar estado Red.
5. Implementar el codigo minimo para Green.
6. Refactorizar sin romper pruebas.
7. Abrir pull request hacia `develop`.
8. Revisar codigo y ejecutar GitHub Actions.
9. Integrar a `main` cuando la entrega sea estable.

## Uso de ramas

- `main`: version estable y entregable.
- `develop`: integracion del equipo.
- `feature/tests-tdd`: pruebas unitarias y evidencia TDD.
- `feature/orm`: modelos, relaciones y pruebas ORM.
- `feature/kata-tdd`: kata de calculo de venta.

## Commits recomendados

- `test: agregar pruebas de calculo de venta`
- `feat: implementar calculadora de total de venta`
- `test: agregar pruebas orm de productos y ventas`
- `docs: documentar ciclo tdd y kata`
- `ci: ejecutar pruebas phpunit en github actions`

## Integracion continua

El archivo `.github/workflows/tests.yml` ejecuta instalacion de dependencias, preparacion de entorno Laravel, migraciones sobre SQLite y pruebas con `php artisan test` en cada push o pull request.

## Integracion de pruebas al desarrollo

Las pruebas son una condicion de aceptacion de cada historia. Ningun cambio debe integrarse si rompe reglas de negocio, persistencia ORM o autenticacion existente. El ciclo TDD ayuda a convertir requisitos en pruebas verificables antes de escribir la implementacion final.

