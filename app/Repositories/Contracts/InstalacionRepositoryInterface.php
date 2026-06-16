<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Instalacion;

interface InstalacionRepositoryInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator;
    public function find(int $tramoId, int $materialId, int $almacenId): ?Instalacion;
    public function create(array $data): Instalacion;
    public function updateComposite(int $tramoId, int $materialId, int $almacenId, array $data): ?Instalacion;
    public function deleteComposite(int $tramoId, int $materialId, int $almacenId): bool;
}
