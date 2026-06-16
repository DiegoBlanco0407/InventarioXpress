<?php

namespace App\Repositories\Eloquent;

use App\Models\Salida;
use App\Repositories\Contracts\SalidaRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SalidaRepository implements SalidaRepositoryInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return Salida::query()->with(['tramoCalle.tramo','tramoCalle.calle','detalles.material'])->paginate($perPage);
    }

    public function find(int $id): ?Salida
    {
        return Salida::with(['tramoCalle.tramo','tramoCalle.calle','detalles.material'])->find($id);
    }

    public function create(array $data): Salida
    {
        return Salida::create($data);
    }

    public function update(int $id, array $data): ?Salida
    {
        $m = Salida::find($id);
        if (!$m) return null;
        $m->fill($data)->save();
        return $m;
    }

    public function delete(int $id): bool
    {
        // Verificar que existe
        $salida = Salida::find($id);
        if (!$salida) return false;
        
        // Usar query builder para eliminación garantizada
        // Primero eliminar detalles
        DB::table('salidas_detalles')->where('id_salida', $salida->id_salida)->delete();
        
        // Luego eliminar la salida
        return (bool)DB::table('salidas')->where('id_salida', $salida->id_salida)->delete();
    }
}
