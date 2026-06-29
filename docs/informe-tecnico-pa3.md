# Informe tecnico academico - Producto Academico n. 3

## Caratula referencial

Universidad Continental  
Asignatura: Construccion de Software  
Producto Academico n. 3  
Actividad: Pruebas Unitarias y TDD  
Proyecto: FarmaGo  
Integrantes: ______________________________  
Lider del equipo: _________________________  
Docente: _________________________________  
Fecha: ___________________________________

## Introduccion

El presente informe describe la adaptacion del proyecto FarmaGo para incorporar pruebas unitarias, enfoque TDD, una kata de dominio, uso verificable de ORM e integracion continua. El objetivo es demostrar calidad tecnica mediante evidencias ejecutables y documentacion formal.

## Descripcion del problema

Las farmacias requieren controlar productos, lotes, vencimientos, stock y ventas con trazabilidad. Sin pruebas automatizadas, los cambios en reglas de venta o inventario pueden generar errores de cobro, stock inconsistente o fallos en reportes.

## Objetivo general

Implementar pruebas unitarias y de integracion con enfoque TDD en FarmaGo, validando reglas de negocio, persistencia ORM e integracion continua.

## Objetivos especificos

- Crear pruebas automatizadas para reglas de calculo de venta.
- Documentar iteraciones Red-Green-Refactor.
- Resolver una kata TDD relacionada al dominio farmaceutico.
- Validar operaciones ORM con Eloquent.
- Configurar GitHub Actions para ejecutar pruebas automaticamente.
- Elaborar documentacion tecnica lista para entrega academica.

## Objetivos segun la actividad evaluativa

OBJ 01: El desarrollo de software contempla la metodologia TDD mediante Katas.

OBJ 02: Se describen los programas desarrollados:

- Codigo limpio para calculo de venta e IGV.
- Katas TDD expresadas mediante pruebas automatizadas.

OBJ 03: Se implementa ORM para gestionar datos del sistema FarmaGo.

## Descripcion del proyecto FarmaGo

FarmaGo es una aplicacion web Laravel para gestion farmaceutica. Administra productos, categorias, lotes, compras, ventas, clientes, usuarios, kardex y reportes. Su dominio prioriza trazabilidad de inventario, control FEFO y gestion de ventas.

## Planteamiento y descripcion detallada de la actividad

La actividad solicita desarrollar un proyecto de software con pruebas unitarias mediante el enfoque de Desarrollo Guiado por Pruebas (TDD). Durante el desarrollo se debe aplicar la metodologia Katas TDD para resolver problemas de forma iterativa y emplear tecnicas de mapeo Objeto-Relacional (ORM) para la gestion eficiente de datos.

Para cumplir con el producto academico, el equipo debe presentar:

1. Un repositorio en GitHub con el codigo fuente gestionado adecuadamente.
2. Un informe tecnico que describa la aplicacion de pruebas unitarias, el enfoque TDD, la resolucion de problemas mediante Katas TDD y el uso de ORM en la gestion de datos.

## Cumplimiento de las indicaciones para el desarrollo

1. Formacion de equipos: el informe deja espacios para completar integrantes, lider del equipo y docente. El README registra los integrantes conocidos del proyecto.
2. Definicion del proyecto: FarmaGo resuelve la gestion farmaceutica de productos, lotes, inventario y ventas.
3. Diseño de pruebas unitarias: se implementaron pruebas unitarias para calculo de venta, IGV, descuentos y validaciones de datos.
4. Aplicacion de TDD: se documento el ciclo Red-Green-Refactor en `docs/tdd-red-green-refactor.md`.
5. Resolucion de problemas con Katas TDD: se desarrollo una kata de calculo de total de venta farmaceutica con IGV.
6. Uso de ORM: se uso Eloquent ORM con modelos, relaciones, scopes, consultas filtradas y eager loading.
7. Integracion de pruebas en el flujo agil: se propuso flujo de ramas y se configuro GitHub Actions.
8. Documentacion y reporte: se elaboraron documentos tecnicos en `docs/`, incluyendo este informe.
9. Entrega del proyecto: el proyecto queda listo para subirse a GitHub y entregar el informe en formato Word.

## Tecnologias utilizadas

- PHP 8.1 o superior.
- Laravel 10.
- Eloquent ORM.
- PHPUnit 10.
- SQLite en memoria para pruebas.
- MySQL/MariaDB en entorno local XAMPP.
- Blade, AdminLTE, Tailwind CSS y Vite.
- GitHub Actions.

## Diseño e implementacion de pruebas unitarias

Se implemento `tests/Unit/VentaTotalCalculatorTest.php` para cubrir casos correctos, descuentos, productos exonerados, productos inafectos, ventas vacias, cantidades invalidas y descuentos invalidos. La logica probada se encuentra en `app/Services/VentaTotalCalculator.php`.

Las pruebas validan multiples escenarios:

- Caso correcto: venta gravada con IGV.
- Caso limite: productos exonerados e inafectos sin IGV.
- Caso de negocio: descuento valido aplicado al item.
- Caso invalido: venta sin items.
- Caso invalido: cantidad cero o negativa.
- Caso invalido: descuento mayor al subtotal.

## Aplicacion del enfoque TDD Red-Green-Refactor

El ciclo se documento en `docs/tdd-red-green-refactor.md`. Primero se definieron pruebas que fallaban por ausencia de implementacion, luego se agrego el codigo minimo y finalmente se refactorizo la organizacion de reglas, validaciones y redondeos.

## Desarrollo de Kata TDD

La kata seleccionada fue el calculo del total de venta farmaceutica con IGV. Esta kata extiende el ejemplo academico de calculo de IGV hacia el dominio FarmaGo: determinar subtotal, descuento, base gravada, exonerada, inafecta, IGV y total final.

Proceso de la kata:

1. Se escribio una prueba para calcular IGV de una venta gravada.
2. Se implemento el minimo codigo para obtener subtotal, IGV y total.
3. Se agrego una prueba para descuentos.
4. Se refactorizo el calculo para separar base gravada y descuento total.
5. Se agregaron pruebas para exonerados e inafectos.
6. Se reforzaron validaciones de datos vacios e invalidos.

## Uso de ORM en la gestion de datos

Se uso Eloquent para modelos como `Producto`, `Categoria`, `Lote`, `MovimientoInventario`, `Cliente`, `Venta` y `DetalleVenta`. Las pruebas en `tests/Feature/Orm/FarmaGoOrmTest.php` validan CRUD, eliminacion logica, relaciones, eager loading y scopes.

Operaciones ORM validadas:

- Creacion de productos.
- Lectura de relaciones `Producto-Categoria`.
- Actualizacion de precio y stock minimo.
- Eliminacion logica con SoftDeletes.
- Registro de venta y detalle.
- Carga anticipada de relaciones con `with()`.
- Consultas filtradas por scopes.

## Integracion de pruebas en el flujo agil

El flujo propuesto usa ramas `main`, `develop`, `feature/tests-tdd`, `feature/orm` y `feature/kata-tdd`. Cada cambio debe pasar por pruebas locales y GitHub Actions antes de integrarse.

## Evidencias de ejecucion de pruebas

Comandos sugeridos:

```bash
php artisan test
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature
```

Capturas sugeridas:

- Ejecucion exitosa de `php artisan test`.
- Vista de GitHub Actions en verde.
- Estructura de carpetas `tests/Unit`, `tests/Feature/Orm` y `docs`.

## Evidencias fotograficas sugeridas segun rubrica

Para fortalecer la evaluacion en nivel sobresaliente, se recomienda adjuntar capturas de pantalla del codigo, del sistema en ejecucion y del repositorio GitHub. Cada captura debe incluir un titulo breve y una descripcion que explique que criterio de la rubrica evidencia.

### Evidencia 1: Estructura del proyecto

Captura sugerida: explorador de archivos o VS Code mostrando las carpetas `app/Services`, `tests/Unit`, `tests/Feature/Orm`, `docs` y `.github/workflows`.

Descripcion para el informe: esta captura evidencia la organizacion del proyecto, la ubicacion de pruebas unitarias, pruebas ORM, documentacion tecnica y configuracion de integracion continua.

### Evidencia 2: Codigo de la Kata TDD

Captura sugerida: archivo `app/Services/VentaTotalCalculator.php`, mostrando el metodo `calcular()`, las validaciones de datos invalidos, el calculo de subtotal, descuento, IGV y total.

Descripcion para el informe: esta captura evidencia la solucion de la Kata TDD relacionada con el calculo del total de venta farmaceutica con IGV.

### Evidencia 3: Pruebas unitarias de la Kata

Captura sugerida: archivo `tests/Unit/VentaTotalCalculatorTest.php`, mostrando pruebas de venta gravada, descuento, productos exonerados, productos inafectos y casos invalidos.

Descripcion para el informe: esta captura evidencia el diseno e implementacion de pruebas unitarias con multiples escenarios, incluyendo casos correctos, casos limite y errores.

### Evidencia 4: Pruebas ORM con Eloquent

Captura sugerida: archivo `tests/Feature/Orm/FarmaGoOrmTest.php`, mostrando pruebas de creacion, lectura, actualizacion, eliminacion logica, relaciones y consultas con `with()`.

Descripcion para el informe: esta captura evidencia el uso correcto de Eloquent ORM, relaciones entre modelos y consultas optimizadas mediante eager loading.

### Evidencia 5: Ciclo Red-Green-Refactor documentado

Captura sugerida: archivo `docs/tdd-red-green-refactor.md`, mostrando la tabla de iteraciones con columnas Red, Green y Refactor.

Descripcion para el informe: esta captura evidencia que el desarrollo siguio el ciclo TDD completo y que las iteraciones fueron documentadas.

### Evidencia 6: Ejecucion exitosa de pruebas

Captura sugerida: terminal ejecutando `php artisan test`, mostrando el resultado `Tests: 41 passed (114 assertions)`.

Descripcion para el informe: esta captura evidencia que las pruebas automatizadas se ejecutan correctamente y validan el comportamiento del sistema.

### Evidencia 7: Sistema FarmaGo en ejecucion

Captura sugerida: navegador abierto en `http://127.0.0.1:8000` o `http://127.0.0.1:8000/login`.

Descripcion para el informe: esta captura evidencia que el sistema FarmaGo se ejecuta localmente y esta disponible para el usuario.

### Evidencia 8: Repositorio GitHub

Captura sugerida: pagina principal del repositorio `https://github.com/kobbryant2014-stack/FarmaGo`.

Descripcion para el informe: esta captura evidencia que el codigo fuente fue subido a GitHub y gestionado mediante control de versiones.

### Evidencia 9: GitHub Actions

Captura sugerida: archivo `.github/workflows/tests.yml` o pestana Actions del repositorio mostrando ejecucion del workflow.

Descripcion para el informe: esta captura evidencia la integracion de pruebas automatizadas en el flujo agil mediante integracion continua.

## Resultados obtenidos

El proyecto queda preparado con pruebas automatizadas, documentacion TDD, kata implementada, evidencia ORM y workflow de integracion continua. Esto incrementa la confiabilidad y facilita futuras mejoras.

Resultado de pruebas registrado:

```text
Tests: 41 passed (114 assertions)
```

## Conclusiones

La incorporacion de TDD permite transformar reglas de negocio en pruebas ejecutables. Eloquent facilita expresar relaciones del dominio FarmaGo con claridad. La integracion continua reduce riesgos al validar cambios en cada push o pull request.

## Recomendaciones

- Mantener la disciplina de escribir pruebas antes de nuevas reglas criticas.
- Agregar cobertura para controladores de ventas y compras.
- Usar pull requests con revision obligatoria.
- Registrar capturas de pruebas para anexos academicos.

## Bibliografia

- Beck, K. (2002). Test Driven Development: By Example. Addison-Wesley.
- Fowler, M. (2018). Refactoring: Improving the Design of Existing Code. Addison-Wesley.
- Laravel Documentation. Testing. https://laravel.com/docs/10.x/testing
- Laravel Documentation. Eloquent ORM. https://laravel.com/docs/10.x/eloquent
- PHPUnit Documentation. https://phpunit.de/documentation.html

## Anexos

Comandos utilizados:

```bash
composer install
php artisan key:generate
php artisan migrate --seed
php artisan test
npm install
npm run build
```

Estructura del repositorio:

```text
app/Services/VentaTotalCalculator.php
tests/Unit/VentaTotalCalculatorTest.php
tests/Feature/Orm/FarmaGoOrmTest.php
docs/tdd-red-green-refactor.md
docs/kata-tdd.md
docs/orm.md
docs/flujo-agil.md
docs/informe-tecnico-pa3.md
.github/workflows/tests.yml
```

Enlace del repositorio GitHub: ______________________________

## Anexos fotograficos para completar en Word

Anexo 1: Estructura del proyecto FarmaGo.  
[Pegar captura aqui]

Anexo 2: Codigo de la Kata TDD `VentaTotalCalculator`.  
[Pegar captura aqui]

Anexo 3: Pruebas unitarias de la Kata TDD.  
[Pegar captura aqui]

Anexo 4: Pruebas ORM con Eloquent.  
[Pegar captura aqui]

Anexo 5: Tabla de iteraciones Red-Green-Refactor.  
[Pegar captura aqui]

Anexo 6: Ejecucion exitosa de `php artisan test`.  
[Pegar captura aqui]

Anexo 7: Sistema FarmaGo ejecutandose en navegador.  
[Pegar captura aqui]

Anexo 8: Repositorio GitHub del proyecto.  
[Pegar captura aqui]

Anexo 9: GitHub Actions o workflow de pruebas.  
[Pegar captura aqui]
