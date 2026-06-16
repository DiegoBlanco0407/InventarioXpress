<?php

namespace App\Services;

use App\Repositories\Contracts\AlmacenRepositoryInterface;
use App\Models\Almacen;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AlmacenService
{
    public function __construct(private AlmacenRepositoryInterface $repo) {}

    public function listar(int $perPage = 15): LengthAwarePaginator { return $this->repo->paginate($perPage); }
    public function ver(int $id): ?Almacen { return $this->repo->find($id); }
    public function crear(array $data): Almacen { return $this->repo->create($data); }
    public function actualizar(int $id, array $data): ?Almacen { return $this->repo->update($id, $data); }
    public function eliminar(int $id): bool { return $this->repo->delete($id); }
}
