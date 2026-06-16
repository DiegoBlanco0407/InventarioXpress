<?php

namespace App\Services;

use App\Repositories\Contracts\UsuarioRepositoryInterface;
use App\Models\Usuario;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UsuarioService
{
    public function __construct(private UsuarioRepositoryInterface $repo) {}

    public function listar(int $perPage = 15): LengthAwarePaginator { return $this->repo->paginate($perPage); }
    public function ver(int $id): ?Usuario { return $this->repo->find($id); }
    public function crear(array $data): Usuario { return $this->repo->create($data); }
    public function actualizar(int $id, array $data): ?Usuario { return $this->repo->update($id, $data); }
    public function eliminar(int $id): bool { return $this->repo->delete($id); }
}
