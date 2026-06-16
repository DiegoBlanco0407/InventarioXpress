<?php

namespace App\Repositories\Eloquent;

use App\Models\Stock;
use App\Repositories\Contracts\StockRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StockRepository implements StockRepositoryInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return Stock::query()->with(['almacen', 'material'])->paginate($perPage);
    }

    public function find(int $almacenId, int $materialId): ?Stock
    {
        return Stock::where('id_almacen', $almacenId)
            ->where('id_material', $materialId)
            ->first();
    }

    public function create(array $data): Stock
    {
        return Stock::create($data);
    }

    public function updateComposite(int $almacenId, int $materialId, array $data): ?Stock
    {
        $s = $this->find($almacenId, $materialId);
        if (!$s) return null;
        $s->fill($data)->save();
        return $s;
    }

    public function deleteComposite(int $almacenId, int $materialId): bool
    {
        return Stock::where('id_almacen', $almacenId)
            ->where('id_material', $materialId)
            ->delete() > 0;
    }
}
