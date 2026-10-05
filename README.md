# InventarioXpress

Aplicación web para la **gestión de almacenes e inventario**. Permite controlar materiales, stock, ubicaciones físicas (ciudades, calles y tramos), instalaciones, pedidos de entrada y salidas de material, con gestión de usuarios y roles.

El proyecto es una **SPA** (aplicación de una sola página): backend **API REST con Laravel 12** y frontend **Vue 3**, servido todo desde la misma aplicación.

---

## Tecnologías

### Backend
- **PHP 8.2+**
- **Laravel 12**
- **Laravel Sanctum** — autenticación por tokens
- **MySQL** (configurable a SQLite)

### Frontend
- **Vue 3** (Composition API)
- **Vue Router** — enrutado de la SPA
- **Pinia** — gestión de estado (stores por entidad)
- **Axios** — cliente HTTP contra la API
- **Tailwind CSS** + **daisyUI** — interfaz y temas
- **Vite** — empaquetado y servidor de desarrollo

---

## Funcionalidades

- **Autenticación** con Sanctum: login, registro, perfil, cambio de contraseña y avatar.
- **Usuarios y roles**: alta, edición y borrado de usuarios con control de acceso por rol.
- **Materiales**: catálogo de materiales del almacén.
- **Stock**: control de existencias por almacén y material (clave compuesta).
- **Ubicaciones**: jerarquía de **Ciudades → Calles → Tramos** y relación **Tramo–Calle**.
- **Instalaciones**: material instalado por tramo/material/almacén.
- **Pedidos**: entradas de material con detalle de líneas.
- **Salidas**: salidas de material con detalle de líneas.
- **Dashboard**: panel con estadísticas (`stats/dashboard`).
- **Multialmacén**: las entidades principales están asociadas a un almacén.

---

## Requisitos previos

- PHP 8.2 o superior
- Composer
- Node.js 18+ y npm
- MySQL (o SQLite)
- Opcional: **Laragon** (entorno recomendado en Windows, el proyecto está preparado para `*.test`)

---

## Instalación

```bash
# 1. Clonar el repositorio
git clone https://github.com/DiegoBlanco0407/InventarioXpress.git
cd InventarioXpress

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias JS
npm install

# 4. Crear el archivo de entorno
cp .env.example .env

# 5. Generar la clave de la aplicación
php artisan key:generate
```

### Configurar la base de datos

En el archivo `.env`, con MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=almacen
DB_USERNAME=root
DB_PASSWORD=root
```

Crea la base de datos `almacen` y ejecuta las migraciones:

```bash
php artisan migrate
```

> También puedes usar SQLite: pon `DB_CONNECTION=sqlite` y crea el fichero `database/database.sqlite`.

---

## Ejecución

### Modo desarrollo

Backend y frontend a la vez (Laravel incluye un script `dev`):

```bash
composer dev
```

Esto levanta: servidor PHP, cola de trabajos, logs (Pail) y Vite.

O por separado:

```bash
php artisan serve   # API y aplicación
npm run dev         # frontend con recarga en caliente
```

Con **Laragon** la app queda disponible en `http://proyecto_almacen.test`.

### Compilar para producción

```bash
npm run build
```

---

## Estructura del proyecto

```
app/
  Http/Controllers/API/   # Controladores de la API REST (v1)
  Models/                 # Modelos Eloquent (Almacen, Material, Stock, Pedido, Salida, ...)
database/
  migrations/             # Esquema de la base de datos
resources/
  js/
    components/           # Componentes reutilizables (DataTable, Modal, Navbar, Sidebar...)
    views/                # Vistas por entidad (listado, alta, edición) + Dashboard, Login, etc.
    stores/               # Stores Pinia (uno por entidad)
    router/               # Definición de rutas de la SPA
routes/
  api.php                 # Rutas de la API (prefijo /api/v1)
  web.php                 # Fallback de la SPA
```

---

## API

Todas las rutas cuelgan del prefijo **`/api/v1`**.

Ejemplos:

| Método | Ruta | Descripción |
|--------|------|-------------|
| `POST` | `/api/v1/auth/login` | Iniciar sesión |
| `POST` | `/api/v1/auth/register` | Registro |
| `GET`  | `/api/v1/auth/me` | Usuario autenticado (requiere token) |
| `GET`  | `/api/v1/stats/dashboard` | Estadísticas del panel |
| `GET`  | `/api/v1/almacenes` | Listar almacenes |
| `GET`  | `/api/v1/materiales` | Listar materiales |
| `GET`  | `/api/v1/stock` | Listar stock |
| `GET`  | `/api/v1/pedidos` | Listar pedidos |
| `GET`  | `/api/v1/salidas` | Listar salidas |

Las rutas de mutación de usuarios y algunas actualizaciones están protegidas con **Sanctum** (`auth:sanctum`). Consulta [`SANCTUM_SETUP.md`](SANCTUM_SETUP.md) para la configuración de la autenticación.

> ⚠️ **Nota:** actualmente varias rutas de creación y borrado están abiertas sin autenticación de forma **temporal** (marcadas como `// TEMPORAL` en `routes/api.php`) mientras se termina de configurar Sanctum. Deben protegerse antes de pasar a producción.

---

## Tests

```bash
composer test
# o
php artisan test
```

---

## Licencia

Proyecto desarrollado sobre el framework Laravel (licencia MIT).
