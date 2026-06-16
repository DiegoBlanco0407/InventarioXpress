<?php

namespace App\Services;

use App\Repositories\Contracts\TramoCalleRepositoryInterface;
use App\Models\TramoCalle;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TramoCalleService
{
    public function __construct(private TramoCalleRepositoryInterface $repo) {}

    public function listar(int $perPage = 15): LengthAwarePaginator { return $this->repo->paginate($perPage); }
    public function ver(int $id): ?TramoCalle { return $this->repo->find($id); }
    public function crear(array $data): TramoCalle { return $this->repo->create($data); }
    public function actualizar(int $id, array $data): ?TramoCalle { return $this->repo->update($id, $data); }
    public function eliminar(int $id): bool { return $this->repo->delete($id); }
}
