<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    protected $table = 'pedidos';
    protected $primaryKey = 'id_pedido';  // Cambiar a id_pedido (sin 's')
    public $timestamps = false;
    protected $fillable = ['fecha', 'origen'];

    public function detalles(): HasMany
    {
        return $this->hasMany(PedidoDetalle::class, 'id_pedido', 'id_pedido');  // Corregir FK
    }

    /**
     * Boot method to handle cascade deletion
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($pedido) {
            // Eliminar todos los detalles asociados antes de eliminar el pedido
            $pedido->detalles()->delete();
        });
    }
}
