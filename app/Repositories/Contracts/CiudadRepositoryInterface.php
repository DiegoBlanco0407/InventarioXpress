<?php

namespace App\Repositories\Contracts;

use App\Models\Ciudad;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CiudadRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Ciudad;
    public function create(array $data): Ciudad;
    public function update(int $id, array $data): ?Ciudad;
    public function delete(int $id): bool;
}
