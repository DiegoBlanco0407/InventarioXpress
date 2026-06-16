<?php

namespace App\Services;

use App\Repositories\Contracts\PedidoRepositoryInterface;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PedidoService
{
    public function __construct(private PedidoRepositoryInterface $repo) {}

    public function listar(int $perPage = 15): LengthAwarePaginator { return $this->repo->paginate($perPage); }
    public function ver(int $id): ?Pedido { return $this->repo->find($id); }
    public function crear(array $data): Pedido
    {
        return DB::transaction(function () use ($data) {
            // Crear pedido principal
            $pedido = $this->repo->create([
                'fecha' => $data['fecha'],
                'origen' => $data['origen'],
            ]);

            // Crear detalle
            PedidoDetalle::create([
                'id_pedido' => $pedido->id_pedido,  // Corregir a id_pedido
                'id_material' => (int)$data['id_material'],
                'cantidad' => (int)$data['cantidad'],
                'id_almacen' => (int)$data['id_almacen'],
            ]);

            return $pedido->load('detalles');
        });
    }
    public function actualizar(int $id, array $data): ?Pedido { return $this->repo->update($id, $data); }
    
    public function eliminar(int $id): bool { 
        // El modelo Pedido maneja automáticamente la eliminación en cascada de detalles
        try {
            return DB::transaction(function() use ($id) {
                // Verificar si el pedido existe
                $pedido = $this->repo->find($id);
                if (!$pedido) {
                    Log::warning("Pedido no encontrado: {$id}");
                    return false;
                }
                
                Log::info("Eliminando pedido con ID: {$pedido->id_pedido} y sus detalles asociados");
                
                // El evento 'deleting' del modelo se encargará de eliminar los detalles automáticamente
                $resultado = $this->repo->delete($id);
                
                if ($resultado) {
                    Log::info("Pedido y sus detalles eliminados exitosamente");
                } else {
                    Log::warning("No se pudo eliminar el pedido");
                }
                
                return $resultado;
            });
        } catch (\Exception $e) {
            Log::error("Error al eliminar pedido: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e;
        }
    }
}
