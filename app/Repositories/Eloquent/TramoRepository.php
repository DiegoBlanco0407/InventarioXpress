<?php

namespace App\Repositories\Eloquent;

use App\Models\Tramo;
use App\Repositories\Contracts\TramoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TramoRepository implements TramoRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Tramo::query()->paginate($perPage);
    }

    public function find(int $id): ?Tramo
    {
        return Tramo::find($id);
    }

    public function create(array $data): Tramo
    {
        return Tramo::create($data);
    }

    public function update(int $id, array $data): ?Tramo
    {
        $m = Tramo::find($id);
        if (!$m) return null;
        $m->fill($data)->save();
        return $m;
    }

    public function delete(int $id): bool
    {
        $m = Tramo::find($id);
        return $m ? (bool)$m->delete() : false;
    }
}
