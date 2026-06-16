<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Almacen;

interface AlmacenRepositoryInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator;
    public function find(int $id): ?Almacen;
    public function create(array $data): Almacen;
    public function update(int $id, array $data): ?Almacen;
    public function delete(int $id): bool;
}
