<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AlmacenController;
use App\Http\Controllers\API\MaterialController;
use App\Http\Controllers\API\PedidoController;
use App\Http\Controllers\API\StockController;
use App\Http\Controllers\API\SalidaController;
use App\Http\Controllers\API\InstalacionController;
use App\Http\Controllers\API\InstalacionCrudController;
use App\Http\Controllers\API\CalleController;
use App\Http\Controllers\API\CiudadController;
use App\Http\Controllers\API\TramoController;
use App\Http\Controllers\API\TramoCalleController;
use App\Http\Controllers\API\UsuarioController;
use App\Http\Controllers\API\StatsController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoleController;

Route::prefix('v1')->group(function () {
    // Rutas públicas (lectura)
    Route::get('almacenes', [AlmacenController::class, 'index']);
    Route::get('stats/dashboard', [StatsController::class, 'dashboard']);
    // Roles
    Route::get('roles', [RoleController::class, 'index']);
    // Auth - Rutas públicas
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/register', [AuthController::class, 'register']);
    
    // Auth - Rutas protegidas (requieren autenticación Sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::post('auth/change-password', [AuthController::class, 'changePassword']);
        Route::post('auth/profile', [AuthController::class, 'updateProfile']);
        Route::post('auth/avatar', [AuthController::class, 'uploadAvatar']);
    });
    // Permitir crear almacenes sin autenticación
    Route::post('almacenes', [AlmacenController::class, 'store']);
    // TEMPORAL: Permitir editar y eliminar entidades sin auth hasta configurar Sanctum
    Route::put('almacenes/{id}', [AlmacenController::class, 'update']);
    Route::delete('almacenes/{id}', [AlmacenController::class, 'destroy']);
    
    // TEMPORAL: Permitir eliminar ciudades sin auth
    Route::delete('ciudades/{id}', [CiudadController::class, 'destroy']);
    
    // TEMPORAL: Permitir eliminar calles sin auth
    Route::delete('calles/{id}', [CalleController::class, 'destroy']);
    
    // TEMPORAL: Permitir eliminar tramos sin auth
    Route::delete('tramos/{id}', [TramoController::class, 'destroy']);
    
    // TEMPORAL: Permitir eliminar tramo-calle sin auth
    Route::delete('tramo-calle/{id}', [TramoCalleController::class, 'destroy']);
    
    // TEMPORAL: Permitir eliminar instalaciones sin auth
    Route::delete('instalaciones/{id_tramo}/{id_material}/{id_almacen}', [InstalacionCrudController::class, 'destroy']);
    
    // TEMPORAL: Permitir eliminar salidas sin auth
    Route::delete('salidas/{id}', [SalidaController::class, 'destroy']);
    
    // TEMPORAL: Permitir eliminar materiales sin auth
    Route::delete('materiales/{id}', [MaterialController::class, 'destroy']);
    
    // TEMPORAL: Permitir crear materiales sin auth
    Route::post('materiales', [MaterialController::class, 'store']);
    
    // TEMPORAL: Permitir eliminar pedidos sin auth
    Route::delete('pedidos/{id}', [PedidoController::class, 'destroy']);
    
    Route::get('materiales', [MaterialController::class, 'index']);
    Route::get('pedidos', [PedidoController::class, 'index']);
    // Detalle de pedido (público para lectura de edición)
    Route::get('pedidos/{id}/detalle', [PedidoController::class, 'detalle']);
    Route::get('pedido-detalle', [PedidoController::class, 'detalleQuery']);
    // Permitir crear pedidos sin autenticación
    Route::post('pedidos', [PedidoController::class, 'store']);
    Route::get('stock', [StockController::class, 'index']);
    // Nuevos listados públicos
    Route::get('calles', [CalleController::class, 'index']);
    Route::get('calles/{id}', [CalleController::class, 'show']);
    // Permitir crear calles sin autenticación
    Route::post('calles', [CalleController::class, 'store']);
    Route::get('ciudades', [CiudadController::class, 'index']);
    Route::get('ciudades/{id}', [CiudadController::class, 'show']);
    // Permitir crear ciudades sin autenticación
    Route::post('ciudades', [CiudadController::class, 'store']);
    Route::get('tramos', [TramoController::class, 'index']);
    Route::get('tramos/{id}', [TramoController::class, 'show']);
    // Permitir crear tramos sin autenticación
    Route::post('tramos', [TramoController::class, 'store']);
    Route::get('tramo-calle', [TramoCalleController::class, 'index']);
    Route::get('tramo-calle/{id}', [TramoCalleController::class, 'show']);
    // Permitir crear tramo-calle sin autenticación
    Route::post('tramo-calle', [TramoCalleController::class, 'store']);
    Route::get('usuarios', [UsuarioController::class, 'index']);
    Route::get('usuarios/{id}', [UsuarioController::class, 'show']);
    // Exponer GET públicos para instalaciones y salidas
    Route::get('instalaciones', [InstalacionCrudController::class, 'index']);
    Route::get('instalaciones/{id_tramo}/{id_material}/{id_almacen}', [InstalacionCrudController::class, 'show']);
    // Permitir crear instalaciones sin autenticación
    Route::post('instalaciones', [InstalacionCrudController::class, 'store']);
    Route::get('salidas', [SalidaController::class, 'index']);
    Route::get('salidas/{id}', [SalidaController::class, 'show']);
    // Detalle de salida (público para lectura de edición)
    Route::get('salidas/{id}/detalle', [SalidaController::class, 'detalle']);
    Route::get('salida-detalle', [SalidaController::class, 'detalleQuery']);
    // Permitir crear salidas sin autenticación
    Route::post('salidas', [SalidaController::class, 'store']);
    
    Route::middleware('auth:sanctum')->group(function () {
        // Mutaciones protegidas
        // NOTA: Almacenes ahora son públicos temporalmente (ver arriba)
        // Route::apiResource('almacenes', AlmacenController::class)->except(['index','store']);
        
        // Permitir solo update para materiales con auth (delete ya está expuesto sin auth)
        Route::put('materiales/{id}', [MaterialController::class, 'update']);
        // Permitir solo update para pedidos con auth (delete ya está expuesto sin auth)
        Route::put('pedidos/{id}', [PedidoController::class, 'update']);
        // Stock con clave compuesta (lecturas/actualizaciones específicas protegidas)
        Route::post('stock', [StockController::class, 'store']);
        Route::get('stock/{id_almacen}/{id_material}', [StockController::class, 'showComposite']);
        Route::put('stock/{id_almacen}/{id_material}', [StockController::class, 'updateComposite']);
        Route::delete('stock/{id_almacen}/{id_material}', [StockController::class, 'destroyComposite']);
        // Permitir solo update para salidas con auth (delete ya está expuesto sin auth)
        Route::put('salidas/{id}', [SalidaController::class, 'update']);
        // Instalaciones con clave compuesta (mutaciones)
        Route::put('instalaciones/{id_tramo}/{id_material}/{id_almacen}', [InstalacionCrudController::class, 'update']);
        // Route::delete('instalaciones/{id_tramo}/{id_material}/{id_almacen}', [InstalacionCrudController::class, 'destroy']); // MOVIDO: Ahora es accesible sin auth
        // Mutaciones de nuevas entidades
        Route::put('calles/{id}', [CalleController::class, 'update']);
        // Route::delete('calles/{id}', [CalleController::class, 'destroy']); // MOVIDO: Ahora es accesible sin auth
        Route::put('ciudades/{id}', [CiudadController::class, 'update']);
        // Route::delete('ciudades/{id}', [CiudadController::class, 'destroy']); // MOVIDO: Ahora es accesible sin auth
        Route::put('tramos/{id}', [TramoController::class, 'update']);
        // Route::delete('tramos/{id}', [TramoController::class, 'destroy']); // MOVIDO: Ahora es accesible sin auth
        Route::put('tramo-calle/{id}', [TramoCalleController::class, 'update']);
        // Route::delete('tramo-calle/{id}', [TramoCalleController::class, 'destroy']); // MOVIDO: Ahora es accesible sin auth
        
        // Gestión de usuarios (requiere autenticación y rol admin)
        Route::post('usuarios', [UsuarioController::class, 'store']);
        Route::put('usuarios/{id}', [UsuarioController::class, 'update']);
        Route::delete('usuarios/{id}', [UsuarioController::class, 'destroy']);
    });
});