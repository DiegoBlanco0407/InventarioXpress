<?php

namespace App\Repositories\Eloquent;

use App\Models\Usuario;
use App\Repositories\Contracts\UsuarioRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UsuarioRepository implements UsuarioRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Usuario::query()->with('rolRef')->paginate($perPage);
    }

    public function find(int $id): ?Usuario
    {
        return Usuario::with('rolRef')->find($id);
    }

    public function create(array $data): Usuario
    {
        if (!empty($data['password'])) {
            $data['password_hash'] = Hash::make($data['password']);
            unset($data['password']);
        }
        return Usuario::create($data);
    }

    public function update(int $id, array $data): ?Usuario
    {
        $m = Usuario::find($id);
        if (!$m) return null;
        if (!empty($data['password'])) {
            $data['password_hash'] = Hash::make($data['password']);
            unset($data['password']);
        }
        $m->fill($data)->save();
        return $m;
    }

    public function delete(int $id): bool
    {
        $m = Usuario::find($id);
        return $m ? (bool)$m->delete() : false;
    }
}
