<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\InstalacionService;

class InstalacionCrudController extends BaseApiController
{
    public function __construct(private InstalacionService $service) {}

    public function index(Request $request)
    {
        $perPage = (int)($request->get('per_page', 15));
        return $this->success($this->service->listar($perPage), 'Listado de instalaciones');
    }

    public function show(int $id_tramo, int $id_material, int $id_almacen)
    {
        $m = $this->service->verComposite($id_tramo, $id_material, $id_almacen);
        return $m ? $this->success($m, 'Detalle de instalación') : $this->error('Instalación no encontrada', 404);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_tramo' => 'required|integer|exists:tramos,id_tramo',
            'id_material' => 'required|integer|exists:materiales,id_material',
            'instalado' => 'required|integer|min:0',
            'a_instalar' => 'required|integer|min:0',
            'id_almacen' => 'required|integer|exists:almacenes,id_almacen',
        ]);
        return $this->success($this->service->crear($data), 'Instalación creada', 201);
    }

    public function update(Request $request, int $id_tramo, int $id_material, int $id_almacen)
    {
        $data = $request->validate([
            'instalado' => 'sometimes|integer|min:0',
            'a_instalar' => 'sometimes|integer|min:0',
        ]);
        $m = $this->service->actualizarComposite($id_tramo, $id_material, $id_almacen, $data);
        return $m ? $this->success($m, 'Instalación actualizada') : $this->error('Instalación no encontrada', 404);
    }

    public function destroy(int $id_tramo, int $id_material, int $id_almacen)
    {
        return $this->service->eliminarComposite($id_tramo, $id_material, $id_almacen)
            ? $this->success(null, 'Instalación eliminada')
            : $this->error('Instalación no encontrada', 404);
    }
}
