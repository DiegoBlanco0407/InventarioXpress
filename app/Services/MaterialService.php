<?php

namespace App\Services;

use App\Repositories\Contracts\MaterialRepositoryInterface;
use App\Models\Material;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MaterialService
{
    public function __construct(private MaterialRepositoryInterface $repo) {}

    public function listar(int $perPage = 15): LengthAwarePaginator { return $this->repo->paginate($perPage); }
    public function ver(int $id): ?Material { return $this->repo->find($id); }
    public function crear(array $data): Material { return $this->repo->create($data); }
    public function actualizar(int $id, array $data): ?Material { return $this->repo->update($id, $data); }
    public function eliminar(int $id): bool { return $this->repo->delete($id); }
}
