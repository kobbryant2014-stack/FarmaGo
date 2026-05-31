# Base de datos de FarmaGo

No hay un archivo `.sql` versionado en este repositorio.

Para Railway MySQL, exporte la base de datos local desde phpMyAdmin como `farmago.sql` e importela en la base de datos MySQL creada en Railway.

Tambien puede crear el esquema con migraciones:

```bash
php artisan migrate --force
php artisan db:seed --force
```

Use migraciones y seeders solo si contienen todos los datos iniciales necesarios para su despliegue.

