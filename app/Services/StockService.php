<?php

namespace App\Services;

use App\Repositories\Contracts\StockRepositoryInterface;
use App\Models\Stock;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StockService
{
    public function __construct(private StockRepositoryInterface $repo) {}

    public function listar(int $perPage = 15): LengthAwarePaginator { return $this->repo->paginate($perPage); }
    public function ver(int $almacenId, int $materialId): ?Stock { return $this->repo->find($almacenId, $materialId); }
    public function crear(array $data): Stock { return $this->repo->create($data); }
    public function actualizar(int $almacenId, int $materialId, array $data): ?Stock { return $this->repo->updateComposite($almacenId, $materialId, $data); }
    public function eliminar(int $almacenId, int $materialId): bool { return $this->repo->deleteComposite($almacenId, $materialId); }
}
