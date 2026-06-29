# FarmaGo - Sistema de Gestion Farmaceutica

## Descripcion del proyecto

FarmaGo es una aplicacion web desarrollada en Laravel 10 para la gestion integral de farmacias y boticas. Permite administrar productos, categorias, lotes, compras, ventas, clientes, usuarios, kardex, reportes y control de inventario con enfoque FEFO.

## Integrantes del equipo

- Brady Palma Rodriguez
- Anthony Luck Aliaga Navarro
- Heather Belen Paulino Torres

## Lider del equipo

Completar: ______________________________

## Problema que resuelve

El sistema ayuda a reducir descontrol de stock, ventas con datos inconsistentes, productos vencidos y falta de trazabilidad en operaciones farmaceuticas.

## Objetivos del proyecto

- Gestionar productos farmaceuticos y categorias.
- Controlar lotes, vencimientos y movimientos de inventario.
- Registrar ventas y detalles de venta.
- Generar reportes operativos.
- Incorporar pruebas unitarias, TDD, ORM e integracion continua para el Producto Academico n. 3.

## Tecnologias usadas

- PHP 8.1+
- Laravel 10
- Eloquent ORM
- PHPUnit 10
- SQLite para pruebas automatizadas
- MySQL/MariaDB para entorno local con XAMPP
- Laravel Breeze
- Spatie Laravel Permission
- Blade, AdminLTE, Tailwind CSS y Vite
- GitHub Actions

## Instalacion

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

## Configuracion de base de datos

Para XAMPP/MySQL, crear una base de datos llamada `farmago` y configurar `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=farmago
DB_USERNAME=root
DB_PASSWORD=
```

Ejecutar migraciones y seeders:

```bash
php artisan migrate --seed
```

## Ejecucion del sistema

```bash
php artisan serve
npm run dev
```

En XAMPP tambien puede accederse desde:

```text
http://localhost/FarmaGo/public
```

## Ejecucion de pruebas

```bash
php artisan test
```

Pruebas por suite:

```bash
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature
```

La configuracion de PHPUnit usa SQLite en memoria durante pruebas.

## Enfoque TDD

Se aplico el ciclo Red-Green-Refactor para la kata de calculo de venta y para validar reglas ORM. La evidencia esta en:

- `docs/tdd-red-green-refactor.md`
- `tests/Unit/VentaTotalCalculatorTest.php`

## Kata TDD

La kata implementada resuelve el calculo de total de venta farmaceutica con cantidad, precio unitario, descuento, afectacion tributaria e IGV. Documentacion:

- `docs/kata-tdd.md`
- `app/Services/VentaTotalCalculator.php`

## ORM

FarmaGo usa Eloquent ORM para productos, categorias, lotes, movimientos, clientes, ventas y detalles. Las pruebas validan CRUD, relaciones, scopes, eliminacion logica y consultas con eager loading:

- `docs/orm.md`
- `tests/Feature/Orm/FarmaGoOrmTest.php`

## Integracion continua

GitHub Actions ejecuta las pruebas en cada push o pull request:

- `.github/workflows/tests.yml`

## Flujo agil y ramas sugeridas

- `main`: version estable.
- `develop`: integracion del equipo.
- `feature/tests-tdd`: pruebas unitarias y evidencia TDD.
- `feature/orm`: pruebas y mejoras ORM.
- `feature/kata-tdd`: kata de calculo de venta.

Documento:

- `docs/flujo-agil.md`

## Documentacion academica PA3

- `docs/informe-tecnico-pa3.md`
- `docs/tdd-red-green-refactor.md`
- `docs/kata-tdd.md`
- `docs/orm.md`
- `docs/flujo-agil.md`

## Comandos utiles

```bash
composer validate
php artisan migrate:fresh --seed
php artisan test
npm run build
```
