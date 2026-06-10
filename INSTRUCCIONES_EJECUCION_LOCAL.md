# Instrucciones de Ejecución Local - FarmaGo

## Requisitos mínimos

- Windows 10/11
- PHP 8.2 instalado y en el PATH
- Composer instalado y en el PATH
- XAMPP o similar (opcional para Apache/MySQL, pero no obligatorio para SQLite)
- Node.js y npm instalados (solo si se requiere compilar assets)

## Preparación del proyecto

1. Copiar el proyecto completo a:
   `C:\xampp\htdocs\FarmaGo`

2. Verificar que exista el archivo `.env` en la raíz del proyecto.
   - El proyecto usa SQLite localmente.
   - La ruta de la base de datos debe ser:
     `DB_CONNECTION=sqlite`
     `DB_DATABASE=C:\xampp\htdocs\FarmaGo\database\database.sqlite`

3. Si no existe el archivo `.env`, copiar `.env.example` a `.env` y ajustar las variables.

## Instalación de dependencias PHP

Desde PowerShell en la carpeta del proyecto:

```powershell
cd C:\xampp\htdocs\FarmaGo
composer install --no-interaction --prefer-dist
```

## Migraciones y base de datos

El proyecto ya incluye una base de datos SQLite (`database/database.sqlite`) y las migraciones se han ejecutado.

Para verificar o aplicar migraciones:

```powershell
php artisan migrate:status
php artisan migrate
```

## Limpieza de cache

Ejecutar:

```powershell
php artisan optimize:clear
```

## Ejecución local

Para iniciar el servidor de desarrollo:

```powershell
php artisan serve --host=127.0.0.0 --port=8091
```

Abrir en el navegador:

`http://127.0.0.1:8091`

## Inicio rápido con script

Se agregaron scripts de inicio en la raíz del proyecto:

- `start-farmago.ps1`
- `start-farmago.bat`

Ejecuta uno de estos archivos para arrancar el servidor en `0.0.0.0:8091`.

## Autoarranque después de reiniciar

Si quieres que el servidor se inicie automáticamente al iniciar sesión, crea una tarea programada de Windows ejecutando PowerShell como administrador:

```powershell
schtasks /create /tn "FarmaGo Start" /tr "powershell -NoProfile -ExecutionPolicy Bypass -File C:\xampp\htdocs\FarmaGo\start-farmago.ps1" /sc onlogon /rl highest /f
```

Esto iniciará la aplicación cada vez que el usuario inicie sesión.

## Usuario administrador existente

Se encontró un usuario administrador en la base de datos:

- Email: `admin@farmago.com`
- Nombre: `Administrador General`

Si no conoces la contraseña, debes resetearla o crear un nuevo usuario administrativo.

## Comandos útiles

```powershell
php artisan test
php artisan migrate:status
php artisan optimize:clear
npm install
npm run build
```

## Logo personalizado

Para usar tu logo nuevo, coloca el archivo de imagen en:

`C:\xampp\htdocs\FarmaGo\public\images\farmago-logo.png`

El sistema cargará automáticamente ese logo si existe; de lo contrario seguirá mostrando el icono SVG por defecto.

## Nota importante

No elimines archivos en `database/`, `storage/` o `public/` antes de verificar el funcionamiento. El proyecto usa archivos y datos locales que se requieren para operar correctamente.
