# Contribucion al repositorio FarmaGo

Este documento define las reglas de colaboracion para trabajar con Git, GitHub y GitFlow en el proyecto academico FarmaGo.

## Clonar el repositorio

```bash
git clone https://github.com/kobbryant2014-stack/FarmaGo.git
cd FarmaGo
```

## Preparar entorno local

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
php artisan test
```

En Windows se puede copiar `.env.example` manualmente si `cp` no esta disponible.

## Crear ramas GitFlow

La rama estable es `main` y la rama de integracion es `develop`.

```bash
git checkout main
git pull origin main
git checkout -b develop
git push -u origin develop
```

Para una funcionalidad:

```bash
git checkout develop
git pull origin develop
git checkout -b feature/ventas-fefo
```

Para preparar una version:

```bash
git checkout develop
git pull origin develop
git checkout -b release/v1.0.0
```

Para una correccion urgente:

```bash
git checkout main
git pull origin main
git checkout -b hotfix/correccion-readme
```

## Commits convencionales

Usar mensajes claros con estos prefijos:

```text
feat: nueva funcionalidad
fix: correccion de error
docs: documentacion
test: pruebas
refactor: mejora interna
chore: mantenimiento
ci: automatizacion
```

Ejemplos:

```bash
git commit -m "feat: implementar ventas con salida fefo"
git commit -m "docs: agregar documentacion gitflow"
git commit -m "ci: agregar workflow laravel ci"
```

## Subir una rama

```bash
git push -u origin feature/ventas-fefo
```

## Crear Pull Requests

1. Abrir GitHub.
2. Seleccionar la rama origen, por ejemplo `feature/ventas-fefo`.
3. Seleccionar la rama destino, normalmente `develop`.
4. Completar la plantilla de Pull Request.
5. Esperar que GitHub Actions finalice.
6. Solicitar revision de otro integrante.
7. Fusionar solo si las pruebas pasan y no hay conflictos.

## Ejecutar pruebas

```bash
composer install
npm install
npm run build
php artisan test
```

Antes de fusionar ramas que afecten base de datos, ejecutar tambien:

```bash
php artisan migrate:fresh --seed
```

## Resolver conflictos

```bash
git checkout develop
git pull origin develop
git checkout feature/ventas-fefo
git merge develop
```

Editar los archivos en conflicto, eliminar marcadores de Git y conservar una version coherente.

```bash
git add .
git commit -m "fix: resolver conflicto entre ventas e inventario"
git push origin feature/ventas-fefo
```

## Preparar un release

```bash
git checkout develop
git pull origin develop
git checkout -b release/v1.0.0
npm run build
php artisan test
git add .
git commit -m "chore: preparar release academico v1.0.0"
git push -u origin release/v1.0.0
```

Despues se abre un Pull Request de `release/v1.0.0` hacia `main`. Cuando se apruebe y se fusione:

```bash
git checkout main
git pull origin main
git tag -a v1.0.0 -m "Version academica 1.0.0"
git push origin v1.0.0
```

## Archivos sensibles

No subir:

- `.env`
- `vendor`
- `node_modules`
- claves en `storage`
- bases SQLite locales
- credenciales o tokens
