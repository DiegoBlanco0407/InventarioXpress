<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Stock;

interface StockRepositoryInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator;
    public function find(int $almacenId, int $materialId): ?Stock;
    public function create(array $data): Stock;
    public function updateComposite(int $almacenId, int $materialId, array $data): ?Stock;
    public function deleteComposite(int $almacenId, int $materialId): bool;
}
