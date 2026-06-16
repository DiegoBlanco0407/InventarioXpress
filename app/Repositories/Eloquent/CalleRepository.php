<?php

namespace App\Repositories\Eloquent;

use App\Models\Calle;
use App\Repositories\Contracts\CalleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CalleRepository implements CalleRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Calle::query()->with('ciudad')->paginate($perPage);
    }

    public function find(int $id): ?Calle
    {
        return Calle::with('ciudad')->find($id);
    }

    public function create(array $data): Calle
    {
        return Calle::create($data);
    }

    public function update(int $id, array $data): ?Calle
    {
        $m = Calle::find($id);
        if (!$m) return null;
        $m->fill($data)->save();
        return $m;
    }

    public function delete(int $id): bool
    {
        $m = Calle::find($id);
        return $m ? (bool)$m->delete() : false;
    }
}
