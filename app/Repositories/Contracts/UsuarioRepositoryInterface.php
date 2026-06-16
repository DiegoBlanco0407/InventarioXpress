<?php

namespace App\Repositories\Contracts;

use App\Models\Usuario;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UsuarioRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Usuario;
    public function create(array $data): Usuario;
    public function update(int $id, array $data): ?Usuario;
    public function delete(int $id): bool;
}
