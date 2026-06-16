<?php

namespace App\Services;

use App\Repositories\Contracts\SalidaRepositoryInterface;
use App\Models\Salida;
use App\Models\SalidaDetalle;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalidaService
{
    public function __construct(private SalidaRepositoryInterface $repo) {}

    public function listar(int $perPage = 15): LengthAwarePaginator { return $this->repo->paginate($perPage); }
    public function ver(int $id): ?Salida { return $this->repo->find($id); }
    public function crear(array $data): Salida
    {
        return DB::transaction(function () use ($data) {
            // Crear registro en salidas
            $salidaPayload = [
                'fecha' => $data['fecha'],
                'id_tramo_calle' => $data['id_tramo_calle'],
            ];
            if (array_key_exists('destinacion', $data)) {
                $salidaPayload['destinacion'] = $data['destinacion'];
            }
            $salida = $this->repo->create($salidaPayload);

            // Crear detalle asociado
            SalidaDetalle::create([
                'id_salida' => $salida->id_salida,
                'id_material' => (int)$data['id_material'],
                'cantidad' => (int)$data['cantidad'],
                'id_almacen' => (int)$data['id_almacen'],
            ]);

            return $salida->load(['detalles']);
        });
    }
    public function actualizar(int $id, array $data): ?Salida { return $this->repo->update($id, $data); }
    
    public function eliminar(int $id): bool {
        // El modelo Salida maneja automáticamente la eliminación en cascada de detalles
        try {
            return DB::transaction(function() use ($id) {
                // Verificar si la salida existe
                $salida = $this->repo->find($id);
                if (!$salida) {
                    Log::warning("Salida no encontrada: {$id}");
                    return false;
                }
                
                Log::info("Eliminando salida con ID: {$salida->id_salida} y sus detalles asociados");
                
                // El evento 'deleting' del modelo se encargará de eliminar los detalles automáticamente
                $resultado = $this->repo->delete($id);
                
                if ($resultado) {
                    Log::info("Salida y sus detalles eliminados exitosamente");
                } else {
                    Log::warning("No se pudo eliminar la salida");
                }
                
                return $resultado;
            });
        } catch (\Exception $e) {
            Log::error("Error al eliminar salida: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e;
        }
    }
}
