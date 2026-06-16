<?php

namespace App\Repositories\Eloquent;

use App\Models\Almacen;
use App\Repositories\Contracts\AlmacenRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AlmacenRepository implements AlmacenRepositoryInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return Almacen::query()->with('ciudadRef')->paginate($perPage);
    }

    public function find(int $id): ?Almacen
    {
        return Almacen::with('ciudadRef')->find($id);
    }

    public function create(array $data): Almacen
    {
        return Almacen::create($data);
    }

    public function update(int $id, array $data): ?Almacen
    {
        $m = Almacen::find($id);
        if (!$m) return null;
        $m->fill($data)->save();
        return $m;
    }

    public function delete(int $id): bool
    {
        $m = Almacen::find($id);
        return $m ? (bool)$m->delete() : false;
    }
}
