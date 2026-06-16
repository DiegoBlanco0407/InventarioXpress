<?php

namespace App\Repositories\Eloquent;

use App\Models\Ciudad;
use App\Repositories\Contracts\CiudadRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CiudadRepository implements CiudadRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Ciudad::query()->paginate($perPage);
    }

    public function find(int $id): ?Ciudad
    {
        return Ciudad::find($id);
    }

    public function create(array $data): Ciudad
    {
        return Ciudad::create($data);
    }

    public function update(int $id, array $data): ?Ciudad
    {
        $m = Ciudad::find($id);
        if (!$m) return null;
        $m->fill($data)->save();
        return $m;
    }

    public function delete(int $id): bool
    {
        $m = Ciudad::find($id);
        return $m ? (bool)$m->delete() : false;
    }
}
