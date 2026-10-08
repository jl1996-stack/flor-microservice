# Microservicio de Flores

Microservicio backend en PHP 8.3 con SQLite (`tessa.db`), separación de responsabilidades y endpoints REST.

## Estructura

- `src/config`: conexión a base de datos.
- `src/repositories`: acceso a datos.
- `src/services`: consumo del proveedor externo.
- `src/controllers`: lógica HTTP.
- `src/routes`: rutas.
- `public/index.html`: interfaz HTML de demostración.
- `Dockerfile` y `docker-compose.yml`: ejecución con Docker.

## Endpoints

### POST /flores

Crea un registro en `VariedadFlor`.

```json
{
  "nombre": "Rosa",
  "color": "Rojo",
  "precio_tallo": 1.50
}
```

### GET /flores

Lista todas las flores y agrega el campo `descripcion_agronomica` obtenido desde:

`https://jsonplaceholder.typicode.com/posts/1`

El valor utilizado es el campo `body` de la respuesta del proveedor externo.

## Ejecutar

```bash
docker compose up --build
```

Abrir `http://localhost:8080`.

Probar API:

```bash
curl http://localhost:8080/flores

curl -X POST http://localhost:8080/flores \\
  -H 'Content-Type: application/json' \\
  -d '{"nombre":"Rosa","color":"Rojo","precio_tallo":1.5}'
```

## Publicar en GitHub

Crear un repositorio público vacío y ejecutar:

```bash
git init
git add .
git commit -m "feat: microservicio de flores"
git branch -M main
git remote add origin https://github.com/<usuario>/<repositorio>.git
git push -u origin main
```
