<?php

namespace App\Services;

use App\Repositories\Contracts\CiudadRepositoryInterface;
use App\Models\Ciudad;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CiudadService
{
    public function __construct(private CiudadRepositoryInterface $repo) {}

    public function listar(int $perPage = 15): LengthAwarePaginator { return $this->repo->paginate($perPage); }
    public function ver(int $id): ?Ciudad { return $this->repo->find($id); }
    public function crear(array $data): Ciudad { return $this->repo->create($data); }
    public function actualizar(int $id, array $data): ?Ciudad { return $this->repo->update($id, $data); }
    public function eliminar(int $id): bool { return $this->repo->delete($id); }
}
