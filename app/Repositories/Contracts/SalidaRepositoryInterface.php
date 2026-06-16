<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Salida;

interface SalidaRepositoryInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator;
    public function find(int $id): ?Salida;
    public function create(array $data): Salida;
    public function update(int $id, array $data): ?Salida;
    public function delete(int $id): bool;
}
