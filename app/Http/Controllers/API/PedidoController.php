<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\PedidoService;
use App\Models\PedidoDetalle;
use Illuminate\Support\Facades\Log;

class PedidoController extends BaseApiController
{
    public function __construct(private PedidoService $service) {}

    public function index() {
        return $this->success($this->service->listar(), 'Listado de pedidos obtenido');
    }
    public function show(int $id) { $m=$this->service->ver($id); return $m? $this->success($m,'Detalle de pedido obtenido'):$this->error('Pedido no encontrado',404);}    
    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha' => 'sometimes|date',
            'origen' => 'required|string|max:255',
            'id_material' => 'required|integer|exists:materiales,id_material',
            'cantidad' => 'required|integer|min:1',
            'id_almacen' => 'required|integer|exists:almacenes,id_almacen',
        ]);
        if (!isset($data['fecha'])) { $data['fecha'] = now()->toDateString(); }
        $m = $this->service->crear($data);
        return $this->success($m, 'Pedido creado', 201);
    }    
    public function update(Request $request, int $id) { $data=$request->validate(['fecha'=>'sometimes|date','origen'=>'sometimes|string|max:255']); $m=$this->service->actualizar($id,$data); return $m? $this->success($m,'Pedido actualizado'):$this->error('Pedido no encontrado',404);}    
    public function destroy(int $id) { 
        try {
            $resultado = $this->service->eliminar($id);
            return $resultado 
                ? $this->success(null, 'Pedido eliminado correctamente')
                : $this->error('Pedido no encontrado', 404);
        } catch (\Exception $e) {
            Log::error("Error al eliminar pedido {$id}: " . $e->getMessage());
            return $this->error('Error al eliminar el pedido: ' . $e->getMessage(), 500);
        }
    }

    // NUEVO: obtener 1 detalle por id_pedido (ruta anidada)
    public function detalle(int $id)
    {
        $detalle = PedidoDetalle::with(['material','almacen'])->where('id_pedido', $id)->first();
        return $detalle ? $this->success($detalle, 'Detalle de pedido obtenido') : $this->error('Detalle no encontrado', 404);
    }

    // NUEVO: endpoint de consulta por query (?id_pedido= / ?pedido=)
    public function detalleQuery(Request $request)
    {
        $id = $request->integer('id_pedido') ?: $request->integer('pedido');
        if (!$id) { return $this->error('Parámetro id_pedido o pedido requerido', 422); }
        $detalle = PedidoDetalle::with(['material','almacen'])->where('id_pedido', $id)->first();
        return $detalle ? $this->success($detalle, 'Detalle de pedido obtenido') : $this->error('Detalle no encontrado', 404);
    }
}
