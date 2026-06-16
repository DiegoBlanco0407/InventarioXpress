<?php

namespace App\Repositories\Eloquent;

use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Repositories\Contracts\PedidoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PedidoRepository implements PedidoRepositoryInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return Pedido::query()->with('detalles')->paginate($perPage);
    }

    public function find(int $id): ?Pedido
    {
        return Pedido::with('detalles')->find($id);
    }

    public function create(array $data): Pedido
    {
        return Pedido::create($data);
    }

    public function update(int $id, array $data): ?Pedido
    {
        $m = Pedido::find($id);
        if (!$m) return null;
        $m->fill($data)->save();
        return $m;
    }

    public function delete(int $id): bool
    {
        // Verificar que existe
        $pedido = Pedido::find($id);
        if (!$pedido) return false;
        
        // Usar query builder para eliminación garantizada
        // Primero eliminar detalles
        DB::table('pedido_detalle')->where('id_pedido', $pedido->id_pedido)->delete();
        
        // Luego eliminar el pedido
        return (bool)DB::table('pedidos')->where('id_pedido', $pedido->id_pedido)->delete();
    }
}
