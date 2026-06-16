<?php

namespace App\Repositories\Contracts;

use App\Models\Calle;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CalleRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Calle;
    public function create(array $data): Calle;
    public function update(int $id, array $data): ?Calle;
    public function delete(int $id): bool;
}
