<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\TramoCalleService;

class TramoCalleController extends BaseApiController
{
    public function __construct(private TramoCalleService $service) {}

    public function index(Request $request)
    {
        $perPage = (int)($request->get('per_page', 15));
        return $this->success($this->service->listar($perPage), 'Listado de tramo-calle');
    }

    public function show(int $id)
    {
        $m = $this->service->ver($id);
        return $m ? $this->success($m, 'Detalle de tramo-calle') : $this->error('Tramo-calle no encontrado', 404);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_tramo' => 'required|integer',
            'id_calle' => 'required|integer',
        ]);
        // Verificar existencia con Eloquent para evitar discrepancias de nombres de columnas
        $tramo = \App\Models\Tramo::find($data['id_tramo']);
        if (!$tramo) { return $this->error('Tramo no existe', 422); }
        $calle = \App\Models\Calle::find($data['id_calle']);
        if (!$calle) { return $this->error('Calle no existe', 422); }
        // Evitar duplicados de la misma relación
        $exists = \App\Models\TramoCalle::where('id_tramo', $data['id_tramo'])
            ->where('id_calle', $data['id_calle'])
            ->exists();
        if ($exists) {
            return $this->error('La relación tramo-calle ya existe', 422);
        }
        return $this->success($this->service->crear($data), 'Tramo-calle creado', 201);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'id_tramo' => 'sometimes|integer|exists:tramos,id_tramos',
            'id_calle' => 'sometimes|integer|exists:calles,id_calle',
        ]);
        $m = $this->service->actualizar($id, $data);
        return $m ? $this->success($m, 'Tramo-calle actualizado') : $this->error('Tramo-calle no encontrado', 404);
    }

    public function destroy(int $id)
    {
        return $this->service->eliminar($id)
            ? $this->success(null, 'Tramo-calle eliminado')
            : $this->error('Tramo-calle no encontrado', 404);
    }
}
