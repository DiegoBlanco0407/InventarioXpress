<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\TramoService;

class TramoController extends BaseApiController
{
    public function __construct(private TramoService $service) {}

    public function index(Request $request)
    {
        $perPage = (int)($request->get('per_page', 15));
        return $this->success($this->service->listar($perPage), 'Listado de tramos');
    }

    public function show(int $id)
    {
        $m = $this->service->ver($id);
        return $m ? $this->success($m, 'Detalle de tramo') : $this->error('Tramo no encontrado', 404);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);
        return $this->success($this->service->crear($data), 'Tramo creado', 201);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|string|max:255',
        ]);
        $m = $this->service->actualizar($id, $data);
        return $m ? $this->success($m, 'Tramo actualizado') : $this->error('Tramo no encontrado', 404);
    }

    public function destroy(int $id)
    {
        return $this->service->eliminar($id)
            ? $this->success(null, 'Tramo eliminado')
            : $this->error('Tramo no encontrado', 404);
    }
}
