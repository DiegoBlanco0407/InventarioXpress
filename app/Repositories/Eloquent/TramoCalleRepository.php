<?php

namespace App\Repositories\Eloquent;

use App\Models\TramoCalle;
use App\Repositories\Contracts\TramoCalleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TramoCalleRepository implements TramoCalleRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return TramoCalle::query()->with(['tramo','calle'])->paginate($perPage);
    }

    public function find(int $id): ?TramoCalle
    {
        return TramoCalle::with(['tramo','calle'])->find($id);
    }

    public function create(array $data): TramoCalle
    {
        // Asignar ID manual si la columna no es autoincremental
        if (!isset($data['id_tramo_calle'])) {
            $nextId = (int)(TramoCalle::max('id_tramo_calle') ?? 0) + 1;
            $data['id_tramo_calle'] = $nextId;
        }
        return TramoCalle::create($data);
    }

    public function update(int $id, array $data): ?TramoCalle
    {
        $m = TramoCalle::find($id);
        if (!$m) return null;
        $m->fill($data)->save();
        return $m;
    }

    public function delete(int $id): bool
    {
        $m = TramoCalle::find($id);
        return $m ? (bool)$m->delete() : false;
    }
}
