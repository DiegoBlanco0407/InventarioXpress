<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\CiudadService;

class CiudadController extends BaseApiController
{
    public function __construct(private CiudadService $service) {}

    public function index(Request $request)
    {
        $perPage = (int)($request->get('per_page', 15));
        return $this->success($this->service->listar($perPage), 'Listado de ciudades');
    }

    public function show(int $id)
    {
        $m = $this->service->ver($id);
        return $m ? $this->success($m, 'Detalle de ciudad') : $this->error('Ciudad no encontrada', 404);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);
        return $this->success($this->service->crear($data), 'Ciudad creada', 201);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|string|max:255',
        ]);
        $m = $this->service->actualizar($id, $data);
        return $m ? $this->success($m, 'Ciudad actualizada') : $this->error('Ciudad no encontrada', 404);
    }

    public function destroy(int $id)
    {
        return $this->service->eliminar($id)
            ? $this->success(null, 'Ciudad eliminada')
            : $this->error('Ciudad no encontrada', 404);
    }
}
