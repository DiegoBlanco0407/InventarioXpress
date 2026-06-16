<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\StockService;

class StockController extends BaseApiController
{
    public function __construct(private StockService $service) {}

    public function index() { return $this->success($this->service->listar(), 'Listado de stock obtenido'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_almacen' => 'required|integer|exists:almacenes,id_almacen',
            'id_material' => 'required|integer|exists:materiales,id_material',
            'cantidad' => 'required|numeric|min:0',
        ]);
        
        // Verificar si ya existe stock para esta combinación
        $existente = $this->service->ver($data['id_almacen'], $data['id_material']);
        if ($existente) {
            return $this->error('Ya existe stock para esta combinación de almacén y material', 409);
        }
        
        try {
            $stock = $this->service->crear($data);
            return $this->success($stock, 'Stock creado correctamente', 201);
        } catch (\Exception $e) {
            return $this->error('Error al crear el stock: ' . $e->getMessage(), 500);
        }
    }

    public function showComposite(int $id_almacen, int $id_material)
    {
        $m = $this->service->ver($id_almacen, $id_material);
        return $m? $this->success($m,'Detalle de stock obtenido'):$this->error('Stock no encontrado',404);
    }

    public function updateComposite(Request $request, int $id_almacen, int $id_material)
    {
        $data = $request->validate([
            'cantidad' => 'sometimes|numeric',
            'necesidades' => 'sometimes|numeric',
            'pedir' => 'sometimes|numeric',
        ]);
        $m = $this->service->actualizar($id_almacen, $id_material, $data);
        return $m? $this->success($m,'Stock actualizado'):$this->error('Stock no encontrado',404);
    }

    public function destroyComposite(int $id_almacen, int $id_material)
    {
        $deleted = $this->service->eliminar($id_almacen, $id_material);
        return $deleted 
            ? $this->success(null, 'Stock eliminado correctamente') 
            : $this->error('Stock no encontrado', 404);
    }
}
