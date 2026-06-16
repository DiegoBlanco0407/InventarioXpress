<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\AlmacenService;
use App\Models\Almacen;


class AlmacenController extends BaseApiController
{
    public function __construct(private AlmacenService $service) {}

    public function index()
    {
        $almacenes = Almacen::with('ciudadRef')->get();
        return $this->success($almacenes, 'Listado de almacenes obtenido');
    }

    public function show(int $id)
    {
        $m = $this->service->ver($id);
        return $m ? $this->success($m, 'Detalle de almacén obtenido') : $this->error('Almacén no encontrado', 404);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'ciudad' => 'required|integer',
        ]);
        // Mapear al nombre de columna real en la BD
        $payload = [
            'nombre' => $data['nombre'],
            'id_ciudad' => $data['ciudad'],
        ];
        $m = $this->service->crear($payload);
        return $this->success($m, 'Almacén creado', 201);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'ciudad' => 'sometimes|integer',
        ]);
        $payload = $data;
        if (array_key_exists('ciudad', $data)) {
            $payload['id_ciudad'] = $data['ciudad'];
            unset($payload['ciudad']);
        }
        $m = $this->service->actualizar($id, $payload);
        return $m ? $this->success($m, 'Almacén actualizado') : $this->error('Almacén no encontrado', 404);
    }

    public function destroy(int $id)
    {
        return $this->service->eliminar($id)
            ? $this->success(null, 'Almacén eliminado')
            : $this->error('Almacén no encontrado', 404);
    }
}
