<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\CalleService;

class CalleController extends BaseApiController
{
    public function __construct(private CalleService $service) {}

    public function index(Request $request)
    {
        $perPage = (int)($request->get('per_page', 15));
        return $this->success($this->service->listar($perPage), 'Listado de calles');
    }

    public function show(int $id)
    {
        $m = $this->service->ver($id);
        return $m ? $this->success($m, 'Detalle de calle') : $this->error('Calle no encontrada', 404);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'id_ciudad' => 'required|integer|exists:ciudades,id_ciudad',
        ]);
        return $this->success($this->service->crear($data), 'Calle creada', 201);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'id_ciudad' => 'sometimes|integer|exists:ciudades,id_ciudad',
        ]);
        $m = $this->service->actualizar($id, $data);
        return $m ? $this->success($m, 'Calle actualizada') : $this->error('Calle no encontrada', 404);
    }

    public function destroy(int $id)
    {
        return $this->service->eliminar($id)
            ? $this->success(null, 'Calle eliminada')
            : $this->error('Calle no encontrada', 404);
    }
}
