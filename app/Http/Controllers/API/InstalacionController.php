<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\InstalacionService;

class InstalacionController extends BaseApiController
{
    public function __construct(private InstalacionService $service) {}

    public function index() { return $this->success($this->service->listar(), 'Listado de tramos de red obtenido'); }
    public function showComposite(int $id_tramo, int $id_material) { $m=$this->service->ver($id_tramo,$id_material); return $m? $this->success($m,'Detalle de instalación obtenido'):$this->error('Instalación no encontrada',404);}    
    public function updateComposite(Request $request, int $id_tramo, int $id_material) {
        $data = $request->validate([
            'instalado' => 'sometimes|numeric',
            'a_instalar' => 'sometimes|numeric',
        ]);
        $m = $this->service->actualizar($id_tramo, $id_material, $data);
        return $m? $this->success($m,'Instalación actualizada'):$this->error('Instalación no encontrada',404);
    }
}
