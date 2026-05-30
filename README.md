# FarmaGo - Sistema de Gestion Farmaceutica

## Descripción

FarmaGo es una aplicación web profesional desarrollada en Laravel 10 para la gestión integral de farmacias y boticas. Permite administrar inventario por lotes, ventas con metodología FEFO, compras, reportes y control de usuarios.

## Problema que resuelve

El sistema soluciona el descontrol de stock, productos vencidos y falta de trazabilidad, automatizando el Kardex y las alertas de reposición.

## Tecnologias utilizadas

- PHP 8.1+
- Laravel 10
- Laravel Breeze
- Spatie Laravel Permission
- Blade
- AdminLTE assets
- Tailwind CSS y Vite
- SQLite
- PHPUnit
- Laravel Pint

2. **Importar la Base de Datos:**
   - Abra **phpMyAdmin** (http://localhost/phpmyadmin).
   - Cree una nueva base de datos llamada `farmago`.
   - Seleccione la base de datos y vaya a la pestaña **Importar**.
   - Seleccione el archivo ubicado en: `C:\xampp\htdocs\FarmaGo\database\farmago.sql`.
   - Haga clic en "Importar".

4. **Acceso al Sistema:**
   Abra su navegador y acceda a la siguiente URL:
   http://localhost/FarmaGo/public

*Nota: El acceso directo por `http://localhost/FarmaGo` puede requerir configuración adicional de Apache. Se recomienda usar la carpeta `/public`.*

## Metodologia agil aplicada

Se documento Scrum en la carpeta `docs/`, incluyendo roles, sprint goal, product backlog, sprint planning, review y retrospectiva.

Documentos principales:

- `docs/01_metodologia_agil_scrum.md`
- `docs/02_product_backlog.md`
- `docs/03_sprint_1.md`

## Uso de IA generativa

Se utilizo IA generativa como apoyo para analizar el sistema, detectar riesgos, proponer refactorizaciones, generar vistas base, mejorar validaciones.


## Manejo de excepciones

El sistema usa validaciones con FormRequest, transacciones en compras y ventas, `try/catch`, `report($e)` y mensajes amigables para el usuario.

Documento:

- `docs/05_manejo_excepciones.md`

## Codigo limpio y refactorizacion

La logica de negocio se organiza en servicios, los controladores coordinan peticiones y las validaciones se ubican en FormRequest. Se centralizo el calculo de ventas y se separo el Kardex por producto y por lote.

Documento:

- `docs/06_codigo_limpio_refactorizacion.md`

## Pruebas

Ejecutar pruebas automatizadas:

```bash
php artisan test
```

Ejecutar validaciones adicionales:

```bash
composer validate
./vendor/bin/pint --test
npm run build
```

Matriz de pruebas funcionales:

- `docs/07_pruebas_funcionales.md`

## Estructura del proyecto

```text
FarmaGo/
|-- app/
|   |-- Http/Controllers/
|   |-- Http/Requests/
|   |-- Models/
|   `-- Services/
|-- database/
|   |-- migrations/
|   `-- seeders/
|-- docs/
|-- public/
|-- resources/
|   |-- views/
|   `-- css/
|-- routes/
|-- tests/
|-- composer.json
|-- package.json
`-- README.md
```

## Evidencias para la rubrica

- Aplicacion funcional: rutas, controladores y vistas principales conectadas.
- Metodologia agil: documentos Scrum en `docs/`.
- Uso de IA generativa: documento de apoyo con IA.
- Manejo de excepciones: controladores, servicios y documentacion.
- Codigo limpio: servicios, FormRequest y refactorizaciones.
- Informe tecnico: `docs/08_informe_tecnico.md`.
- Guion de video: `docs/09_guion_video.md`.
- Repositorio Git: rama sugerida `adecuacion-rubrica`.

## Autores Equipo de desarrollo FarmaGo.
* Brady Palma Rodriguez
* Anthony Luck Aliaga Navarro
* Heather Belen Paulino Torres

