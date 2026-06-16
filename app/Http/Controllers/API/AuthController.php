<?php

namespace App\Http\Controllers\API;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseApiController
{
    /**
     * Login: Genera token de Sanctum
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Buscar usuario en tabla usuarios
        $usuario = Usuario::where('email', $data['email'])->first();

        // Verificar password con Bcrypt
        if (!$usuario || !Hash::check($data['password'], $usuario->password_hash)) {
            return $this->error('Credenciales inválidas', 401);
        }

        // Generar token de Sanctum
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return $this->success([
            'token' => $token,
            'usuario' => [
                'id' => $usuario->id_usuario,
                'nombre' => $usuario->nombre,
                'email' => $usuario->email,
                'rol' => $usuario->rol,
                'ruta_imagen' => $usuario->ruta_imagen,
            ]
        ], 'Inicio de sesión correcto');
    }

    /**
     * Register: Crea usuario y genera token de Sanctum
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Rol por defecto (evitar null). Ajusta el valor según tu tabla de roles
        $defaultRol = $request->input('rol', 1);

        $usuario = Usuario::create([
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'password_hash' => Hash::make($data['password']),
            'rol' => $defaultRol,
        ]);

        // Auto login: generar token de Sanctum
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return $this->success([
            'token' => $token,
            'usuario' => [
                'id' => $usuario->id_usuario,
                'nombre' => $usuario->nombre,
                'email' => $usuario->email,
                'rol' => $usuario->rol,
                'ruta_imagen' => $usuario->ruta_imagen,
            ]
        ], 'Usuario registrado', 201);
    }

    /**
     * Me: Obtiene los datos del usuario autenticado
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        $usuario = $request->user();

        if (!$usuario) {
            return $this->error('No autenticado', 401);
        }

        return $this->success([
            'id' => $usuario->id_usuario,
            'nombre' => $usuario->nombre,
            'email' => $usuario->email,
            'rol' => $usuario->rol,
            'ruta_imagen' => $usuario->ruta_imagen,
        ], 'Usuario actual');
    }

    /**
     * Logout: Revoca el token actual del usuario
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Revocar el token actual
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Sesión cerrada');
    }

    /**
     * Change Password: Cambia la contraseña del usuario autenticado
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $usuario = $request->user();

        if (!$usuario) {
            return $this->error('No autenticado', 401);
        }

        $data = $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($data['old_password'], $usuario->password_hash)) {
            return $this->error('La contraseña actual no es correcta', 422);
        }

        $usuario->password_hash = Hash::make($data['password']);
        $usuario->save();

        return $this->success(null, 'Contraseña actualizada correctamente');
    }

    /**
     * Update Profile: Actualiza los datos del perfil del usuario
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $usuario = $request->user();

        if (!$usuario) {
            return $this->error('No autenticado', 401);
        }

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:usuarios,email,' . $usuario->id_usuario . ',id_usuario',
        ]);

        $usuario->nombre = $data['nombre'];
        $usuario->email = $data['email'];
        $usuario->save();

        return $this->success([
            'id' => $usuario->id_usuario,
            'nombre' => $usuario->nombre,
            'email' => $usuario->email,
            'rol' => $usuario->rol,
            'ruta_imagen' => $usuario->ruta_imagen,
        ], 'Perfil actualizado');
    }

    /**
     * Upload Avatar: Sube una imagen de avatar para el usuario
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAvatar(Request $request)
    {
        $usuario = $request->user();

        if (!$usuario) {
            return $this->error('No autenticado', 401);
        }

        $request->validate([
            'avatar' => 'required|image|max:20480',
        ]);

        $file = $request->file('avatar');
        $dir = public_path('imagenes');
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $ext = $file->getClientOriginalExtension();
        $filename = 'user_' . $usuario->id_usuario . '_' . time() . '.' . $ext;
        $file->move($dir, $filename);
        $relativePath = '/imagenes/' . $filename;

        $usuario->ruta_imagen = $relativePath;
        $usuario->save();

        return $this->success([
            'ruta_imagen' => $relativePath,
        ], 'Avatar actualizado');
    }
}
