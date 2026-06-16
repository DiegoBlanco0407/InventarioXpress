<?php

namespace App\Repositories\Contracts;

use App\Models\Tramo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TramoRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Tramo;
    public function create(array $data): Tramo;
    public function update(int $id, array $data): ?Tramo;
    public function delete(int $id): bool;
}
