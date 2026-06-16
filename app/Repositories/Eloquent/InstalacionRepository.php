<?php

namespace App\Repositories\Eloquent;

use App\Models\Instalacion;
use App\Repositories\Contracts\InstalacionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InstalacionRepository implements InstalacionRepositoryInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return Instalacion::query()->with(['tramo', 'tramoCalle.calle', 'material', 'almacen'])->paginate($perPage);
    }

    public function find(int $tramoId, int $materialId, int $almacenId): ?Instalacion
    {
        return Instalacion::where('id_tramo', $tramoId)
            ->where('id_material', $materialId)
            ->where('id_almacen', $almacenId)
            ->first();
    }

    public function create(array $data): Instalacion
    {
        return Instalacion::create($data);
    }

    public function updateComposite(int $tramoId, int $materialId, int $almacenId, array $data): ?Instalacion
    {
        $s = $this->find($tramoId, $materialId, $almacenId);
        if (!$s) return null;
        $s->fill($data)->save();
        return $s;
    }

    public function deleteComposite(int $tramoId, int $materialId, int $almacenId): bool
    {
        // No usar $model->delete() porque el modelo tiene primaryKey = null
        // Usar query directa con where conditions
        $deleted = Instalacion::where('id_tramo', $tramoId)
            ->where('id_material', $materialId)
            ->where('id_almacen', $almacenId)
            ->delete();
        
        return $deleted > 0;
    }
}
