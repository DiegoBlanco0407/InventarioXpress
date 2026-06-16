<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\SalidaService;
use App\Models\SalidaDetalle;
use Illuminate\Support\Facades\Log;

class SalidaController extends BaseApiController
{
    public function __construct(private SalidaService $service) {}

    public function index() { return $this->success($this->service->listar(), 'Listado de salidas obtenido'); }
    public function show(int $id) { $m=$this->service->ver($id); return $m? $this->success($m,'Detalle de salida obtenido'):$this->error('Salida no encontrada',404);}    
    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha' => 'sometimes|date',
            'id_tramo_calle' => 'required|integer|exists:tramo_calle,id_tramo_calle',
            'id_material' => 'required|integer|exists:materiales,id_material',
            'cantidad' => 'required|integer|min:1',
            'id_almacen' => 'required|integer|exists:almacenes,id_almacen',
        ]);
        if (!isset($data['fecha'])) { $data['fecha'] = now()->toDateString(); }
        $m = $this->service->crear($data);
        return $this->success($m, 'Salida creada', 201);
    }    
    public function update(Request $request, int $id) { $data=$request->validate(['fecha'=>'sometimes|date','destinacion'=>'sometimes|string|max:255']); $m=$this->service->actualizar($id,$data); return $m? $this->success($m,'Salida actualizada'):$this->error('Salida no encontrada',404);}    
    
    public function destroy(int $id) {
        try {
            $resultado = $this->service->eliminar($id);
            return $resultado 
                ? $this->success(null, 'Salida eliminada correctamente')
                : $this->error('Salida no encontrada', 404);
        } catch (\Exception $e) {
            Log::error("Error al eliminar salida {$id}: " . $e->getMessage());
            return $this->error('Error al eliminar la salida: ' . $e->getMessage(), 500);
        }
    }

    // NUEVO: obtener 1 detalle por id_salida (ruta anidada)
    public function detalle(int $id)
    {
        $detalle = SalidaDetalle::with(['material','almacen'])->where('id_salida', $id)->first();
        return $detalle ? $this->success($detalle, 'Detalle de salida obtenido') : $this->error('Detalle no encontrado', 404);
    }

    // NUEVO: endpoint de consulta por query (?id_salida= / ?salida=)
    public function detalleQuery(Request $request)
    {
        $id = $request->integer('id_salida') ?: $request->integer('salida');
        if (!$id) { return $this->error('Parámetro id_salida o salida requerido', 422); }
        $detalle = SalidaDetalle::with(['material','almacen'])->where('id_salida', $id)->first();
        return $detalle ? $this->success($detalle, 'Detalle de salida obtenido') : $this->error('Detalle no encontrado', 404);
    }
}
