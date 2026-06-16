# Laravel Sanctum - Guía de Configuración y Uso

## ✅ Configuración Completada

La autenticación con Laravel Sanctum ha sido completamente implementada en tu proyecto.

## 📋 Cambios Realizados

### 1. **Modelo Usuario** (`app/Models/Usuario.php`)
- ✅ Extiende `Authenticatable` en lugar de `Model`
- ✅ Usa el trait `HasApiTokens` de Sanctum
- ✅ Implementa `getAuthPassword()` para mapear `password_hash`
- ✅ Oculta el campo `password_hash` en las respuestas JSON

### 2. **Configuración de Autenticación** (`config/auth.php`)
- ✅ Agregado guard `sanctum` con provider `usuarios`
- ✅ Agregado provider `usuarios` que usa el modelo `Usuario`

### 3. **AuthController** (`app/Http/Controllers/API/AuthController.php`)
- ✅ Reescrito completamente para usar tokens de Sanctum
- ✅ `login()`: Verifica credenciales y genera token de Sanctum
- ✅ `register()`: Crea usuario y genera token automáticamente
- ✅ `me()`: Obtiene datos del usuario autenticado
- ✅ `logout()`: Revoca el token actual
- ✅ `changePassword()`: Cambia contraseña del usuario autenticado
- ✅ `updateProfile()`: Actualiza datos del perfil
- ✅ `uploadAvatar()`: Sube imagen de avatar

### 4. **Rutas API** (`routes/api.php`)
- ✅ Rutas públicas: `auth/login` y `auth/register`
- ✅ Rutas protegidas con `auth:sanctum` middleware:
  - `GET auth/me`
  - `POST auth/logout`
  - `POST auth/change-password`
  - `POST auth/profile`
  - `POST auth/avatar`

### 5. **Middleware y CORS** (`bootstrap/app.php`)
- ✅ Configurado `EnsureFrontendRequestsAreStateful` middleware de Sanctum
- ✅ Excluida validación CSRF para rutas `api/*`

### 6. **Configuración CORS** (`config/cors.php`)
- ✅ Configurado para permitir credenciales
- ✅ Habilitado para rutas `api/*` y `sanctum/csrf-cookie`

### 7. **Configuración Sanctum** (`config/sanctum.php`)
- ✅ Ya existía y está configurado correctamente
- ✅ Dominios stateful incluyen localhost

## 🚀 Instrucciones de Instalación

### Paso 1: Ejecutar Migraciones

Si aún no has ejecutado las migraciones, ejecuta:

```bash
php artisan migrate
```

Esto creará la tabla `personal_access_tokens` necesaria para Sanctum.

### Paso 2: Verificar Configuración del .env

Asegúrate de que tu archivo `.env` tenga configurado:

```env
# URL de tu aplicación
APP_URL=http://localhost:8000

# Dominios stateful de Sanctum (separados por comas)
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:5173,127.0.0.1,127.0.0.1:8000
```

### Paso 3: Limpiar Caché

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## 📡 Uso desde el Frontend Vue

### Ejemplo de Login

```javascript
// En tu store de auth (stores/auth.js)
import axios from 'axios';

const API_URL = 'http://localhost:8000/api/v1';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
    loading: false,
    error: null,
  }),

  actions: {
    async login(email, password) {
      this.loading = true;
      this.error = null;
      
      try {
        const response = await axios.post(`${API_URL}/auth/login`, {
          email,
          password,
        });
        
        // Guardar token y usuario
        this.token = response.data.data.token;
        this.user = response.data.data.usuario;
        
        // Guardar en localStorage
        localStorage.setItem('token', this.token);
        localStorage.setItem('user', JSON.stringify(this.user));
        
        // Configurar header por defecto para futuras peticiones
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        
        return true;
      } catch (error) {
        this.error = error.response?.data?.message || 'Error al iniciar sesión';
        return false;
      } finally {
        this.loading = false;
      }
    },

    async logout() {
      try {
        await axios.post(`${API_URL}/auth/logout`, {}, {
          headers: {
            'Authorization': `Bearer ${this.token}`
          }
        });
      } catch (error) {
        console.error('Error al cerrar sesión', error);
      } finally {
        // Limpiar estado
        this.token = null;
        this.user = null;
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        delete axios.defaults.headers.common['Authorization'];
      }
    },

    async getMe() {
      try {
        const response = await axios.get(`${API_URL}/auth/me`, {
          headers: {
            'Authorization': `Bearer ${this.token}`
          }
        });
        this.user = response.data.data;
      } catch (error) {
        // Token inválido, limpiar sesión
        this.logout();
      }
    },

    // Inicializar desde localStorage
    initAuth() {
      const token = localStorage.getItem('token');
      const user = localStorage.getItem('user');
      
      if (token && user) {
        this.token = token;
        this.user = JSON.parse(user);
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      }
    },
  },
});
```

### Configurar Axios Interceptor (opcional pero recomendado)

```javascript
// En tu archivo main.js o un archivo de configuración de axios
import axios from 'axios';

// Interceptor para agregar el token automáticamente
axios.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Interceptor para manejar errores 401 (no autenticado)
axios.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Redirigir a login
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);
```

## 🧪 Pruebas con Postman/Thunder Client

### 1. Login
```
POST http://localhost:8000/api/v1/auth/login
Content-Type: application/json

{
  "email": "usuario@ejemplo.com",
  "password": "123456"
}
```

**Respuesta esperada:**
```json
{
  "success": true,
  "message": "Inicio de sesión correcto",
  "data": {
    "token": "1|abc123def456...",
    "usuario": {
      "id": 1,
      "nombre": "Usuario Ejemplo",
      "email": "usuario@ejemplo.com",
      "rol": 1,
      "ruta_imagen": null
    }
  }
}
```

### 2. Obtener Usuario Autenticado
```
GET http://localhost:8000/api/v1/auth/me
Authorization: Bearer 1|abc123def456...
```

### 3. Logout
```
POST http://localhost:8000/api/v1/auth/logout
Authorization: Bearer 1|abc123def456...
```

## 🔒 Proteger Rutas Adicionales

Para proteger cualquier otra ruta de tu API con Sanctum:

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('usuarios', [UsuarioController::class, 'index']);
    Route::put('usuarios/{id}', [UsuarioController::class, 'update']);
    // ... más rutas protegidas
});
```

## 🛠️ Troubleshooting

### Error: "Unauthenticated"
- Verifica que el token se esté enviando correctamente en el header `Authorization: Bearer {token}`
- Verifica que el token no haya sido revocado
- Ejecuta `php artisan config:clear`

### Error: CORS
- Verifica que `config/cors.php` esté configurado correctamente
- Verifica que el dominio del frontend esté en `SANCTUM_STATEFUL_DOMAINS` en `.env`
- Asegúrate de que el frontend envíe las credenciales (en axios: `withCredentials: true`)

### La tabla `personal_access_tokens` no existe
- Ejecuta `php artisan migrate` para crear la tabla

## 📚 Más Información

- [Documentación oficial de Laravel Sanctum](https://laravel.com/docs/11.x/sanctum)
- [SPA Authentication con Sanctum](https://laravel.com/docs/11.x/sanctum#spa-authentication)

## ✨ Características Implementadas

- ✅ Login con email y password
- ✅ Register de nuevos usuarios
- ✅ Logout (revocación de tokens)
- ✅ Obtener datos del usuario autenticado
- ✅ Cambio de contraseña
- ✅ Actualización de perfil
- ✅ Subida de avatar
- ✅ Protección de rutas con middleware
- ✅ CORS configurado para SPA
- ✅ Soporte para modelo Usuario personalizado con tabla `usuarios`
- ✅ Mapeo correcto de `password_hash` a `password` para autenticación
