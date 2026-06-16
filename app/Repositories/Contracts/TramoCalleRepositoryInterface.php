<?php

namespace App\Repositories\Contracts;

use App\Models\TramoCalle;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TramoCalleRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?TramoCalle;
    public function create(array $data): TramoCalle;
    public function update(int $id, array $data): ?TramoCalle;
    public function delete(int $id): bool;
}
