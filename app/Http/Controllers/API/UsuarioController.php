<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\UsuarioService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Usuario;

class UsuarioController extends BaseApiController
{
    public function __construct(private UsuarioService $service) {}

    private function verifyToken(?string $token): ?Usuario
    {
        if (!$token || !str_contains($token, '.')) return null;
        [$payload, $sig] = explode('.', $token, 2);
        $key = config('app.key') ?: 'invx-secret';
        $expected = hash_hmac('sha256', $payload, $key);
        if (!hash_equals($expected, $sig)) return null;
        $data = json_decode(base64_decode($payload), true);
        if (!isset($data['uid'])) return null;
        return Usuario::find($data['uid']);
    }

    public function index(Request $request)
    {
        $perPage = (int)($request->get('per_page', 15));
        return $this->success($this->service->listar($perPage), 'Listado de usuarios');
    }

    public function show(int $id)
    {
        $m = $this->service->ver($id);
        return $m ? $this->success($m, 'Detalle de usuario') : $this->error('Usuario no encontrado', 404);
    }

    public function store(Request $request)
    {
        // Solo administradores pueden crear usuarios - usar Sanctum
        $currentUser = $request->user();
        if (!$currentUser) return $this->error('No autenticado', 401);
        if ((int)($currentUser->rol) !== 1) return $this->error('No autorizado', 403);

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'rol' => 'nullable|integer|exists:roles,id_rol',
            'email' => 'required|email|max:255|unique:usuarios,email',
            'password' => 'required|string|min:6',
        ]);
        // Mapear password plano a password_hash
        $payload = [
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'password_hash' => Hash::make($data['password']),
        ];
        // Solo agregar rol si se proporcionó
        if (isset($data['rol']) && $data['rol'] !== null) {
            $payload['rol'] = $data['rol'];
        }
        
        try {
            return $this->success($this->service->crear($payload), 'Usuario creado', 201);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            return $this->error('El email ya está registrado', 422);
        } catch (\Exception $e) {
            Log::error('Error al crear usuario: ' . $e->getMessage());
            return $this->error('Error al crear el usuario', 500);
        }
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'rol' => 'sometimes|integer|exists:roles,id_rol',
            'email' => 'sometimes|email|max:255',
            'password' => 'sometimes|string|min:6',
        ]);
        $payload = $data;
        if (array_key_exists('password', $data)) {
            $payload['password_hash'] = Hash::make($data['password']);
            unset($payload['password']);
        }
        $m = $this->service->actualizar($id, $payload);
        return $m ? $this->success($m, 'Usuario actualizado') : $this->error('Usuario no encontrado', 404);
    }

    public function destroy(int $id)
    {
        return $this->service->eliminar($id)
            ? $this->success(null, 'Usuario eliminado')
            : $this->error('Usuario no encontrado', 404);
    }
}
