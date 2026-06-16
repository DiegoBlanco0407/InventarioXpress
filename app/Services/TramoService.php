<?php

namespace App\Services;

use App\Repositories\Contracts\TramoRepositoryInterface;
use App\Models\Tramo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TramoService
{
    public function __construct(private TramoRepositoryInterface $repo) {}

    public function listar(int $perPage = 15): LengthAwarePaginator { return $this->repo->paginate($perPage); }
    public function ver(int $id): ?Tramo { return $this->repo->find($id); }
    public function crear(array $data): Tramo { return $this->repo->create($data); }
    public function actualizar(int $id, array $data): ?Tramo { return $this->repo->update($id, $data); }
    public function eliminar(int $id): bool { return $this->repo->delete($id); }
}
