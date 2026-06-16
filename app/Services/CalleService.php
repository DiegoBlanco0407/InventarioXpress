<?php

namespace App\Services;

use App\Repositories\Contracts\CalleRepositoryInterface;
use App\Models\Calle;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CalleService
{
    public function __construct(private CalleRepositoryInterface $repo) {}

    public function listar(int $perPage = 15): LengthAwarePaginator { return $this->repo->paginate($perPage); }
    public function ver(int $id): ?Calle { return $this->repo->find($id); }
    public function crear(array $data): Calle { return $this->repo->create($data); }
    public function actualizar(int $id, array $data): ?Calle { return $this->repo->update($id, $data); }
    public function eliminar(int $id): bool { return $this->repo->delete($id); }
}
