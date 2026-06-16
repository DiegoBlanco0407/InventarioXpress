<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Material;

interface MaterialRepositoryInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator;
    public function find(int $id): ?Material;
    public function create(array $data): Material;
    public function update(int $id, array $data): ?Material;
    public function delete(int $id): bool;
}
