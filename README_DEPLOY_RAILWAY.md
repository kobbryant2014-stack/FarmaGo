# Despliegue de FarmaGo en Railway

FarmaGo es una aplicacion Laravel 10. En Railway debe desplegarse con Docker, Apache, PHP 8.2 y MySQL.

## 1. Crear servicios en Railway

1. Cree un nuevo proyecto en Railway.
2. Agregue un servicio desde el repositorio de GitHub de FarmaGo.
3. Agregue un servicio de base de datos MySQL en el mismo proyecto.
4. En el servicio web, vincule las variables del servicio MySQL.

## 2. Variables de entorno requeridas

Configure estas variables en el servicio web de FarmaGo:

```env
APP_NAME=FarmaGo
APP_ENV=production
APP_KEY=base64:GENERE_UNA_CLAVE_LARAVEL
APP_DEBUG=false
APP_URL=https://SU-DOMINIO-RAILWAY
APP_TIMEZONE=America/Lima

DB_CONNECTION=mysql
MYSQLHOST=valor_del_mysql_de_railway
MYSQLPORT=3306
MYSQLUSER=valor_del_mysql_de_railway
MYSQLPASSWORD=valor_del_mysql_de_railway
MYSQLDATABASE=valor_del_mysql_de_railway

CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
LOG_CHANNEL=stack
```

Tambien puede usar las variables Laravel tradicionales `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD` y `DB_DATABASE`. La configuracion actual prioriza las variables `MYSQL*` de Railway y mantiene valores locales por defecto para desarrollo.

Para generar `APP_KEY` localmente:

```bash
php artisan key:generate --show
```

Copie el valor generado en Railway.

## 3. Base de datos

No se encontro un archivo `.sql` versionado en el repositorio. Existe `database.rar`, pero Railway necesita importar un archivo `.sql`.

Opciones:

1. Exportar la base de datos desde phpMyAdmin en XAMPP como `farmago.sql`.
2. Importar ese archivo en Railway MySQL desde un cliente MySQL, MySQL Workbench, DBeaver o la consola que prefiera.
3. Alternativamente, ejecutar migraciones y seeders si desea crear la base desde el esquema Laravel:

```bash
php artisan migrate --force
php artisan db:seed --force
```

Use esta alternativa solo si los seeders contienen todos los datos iniciales que necesita.

## 4. Docker y Apache

El repositorio incluye un `Dockerfile` en la raiz. El contenedor:

- Usa PHP 8.2 con Apache.
- Instala `mysqli`, `pdo`, `pdo_mysql` y `zip`.
- Instala dependencias Composer sin paquetes de desarrollo.
- Compila assets con Vite.
- Habilita `mod_rewrite`.
- Apunta el DocumentRoot de Apache a `/var/www/html/public`.
- Ajusta permisos de `storage` y `bootstrap/cache`.

Railway detectara el `Dockerfile` automaticamente al desplegar desde GitHub.

## 5. Redeploy

1. Suba los cambios a GitHub.
2. En Railway, abra el servicio web.
3. Ejecute **Redeploy** o espere el despliegue automatico del nuevo commit.
4. Revise los logs de build y runtime.

## 6. Si vuelve a fallar

Revise estos puntos:

- El archivo debe llamarse exactamente `Dockerfile`.
- `APP_KEY` debe estar configurado en Railway.
- Las variables `MYSQLHOST`, `MYSQLPORT`, `MYSQLUSER`, `MYSQLPASSWORD` y `MYSQLDATABASE` deben existir en el servicio web.
- El servicio MySQL debe estar creado y vinculado al proyecto.
- Apache debe servir desde `public/`, no desde la raiz del proyecto.
- La base de datos debe estar importada o migrada.
- Si el error ocurre en `npm run build`, revise cambios recientes en `resources/`, `vite.config.js` o dependencias de `package.json`.
- Si el error ocurre en Composer, revise `composer.lock` y la version de PHP requerida.

