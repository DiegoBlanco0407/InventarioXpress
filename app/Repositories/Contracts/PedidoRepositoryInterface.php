<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Pedido;

interface PedidoRepositoryInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator;
    public function find(int $id): ?Pedido;
    public function create(array $data): Pedido;
    public function update(int $id, array $data): ?Pedido;
    public function delete(int $id): bool;
}
