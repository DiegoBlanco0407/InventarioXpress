<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\MaterialService;

class MaterialController extends BaseApiController
{
    public function __construct(private MaterialService $service) {}

    public function index() { return $this->success($this->service->listar(), 'Listado de materiales obtenido'); }
    public function show(int $id) { $m=$this->service->ver($id); return $m? $this->success($m,'Detalle de material obtenido'):$this->error('Material no encontrado',404);}    
    public function store(Request $request) { $data=$request->validate(['concepto'=>'required|string|max:255']); $m=$this->service->crear($data); return $this->success($m,'Material creado',201);}    
    public function update(Request $request, int $id) { $data=$request->validate(['concepto'=>'sometimes|string|max:255']); $m=$this->service->actualizar($id,$data); return $m? $this->success($m,'Material actualizado'):$this->error('Material no encontrado',404);}    
    public function destroy(int $id) { return $this->service->eliminar($id)? $this->success(null,'Material eliminado'):$this->error('Material no encontrado',404);}    
}
