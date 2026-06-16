<?php

namespace App\Repositories\Eloquent;

use App\Models\Material;
use App\Repositories\Contracts\MaterialRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MaterialRepository implements MaterialRepositoryInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return Material::query()->paginate($perPage);
    }

    public function find(int $id): ?Material
    {
        return Material::find($id);
    }

    public function create(array $data): Material
    {
        return Material::create($data);
    }

    public function update(int $id, array $data): ?Material
    {
        $m = Material::find($id);
        if (!$m) return null;
        $m->fill($data)->save();
        return $m;
    }

    public function delete(int $id): bool
    {
        $m = Material::find($id);
        return $m ? (bool)$m->delete() : false;
    }
}
