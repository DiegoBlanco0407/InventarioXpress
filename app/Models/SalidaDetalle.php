<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalidaDetalle extends Model
{
    protected $table = 'salidas_detalles';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null; // posible clave compuesta
    protected $fillable = ['id_material', 'id_salida', 'cantidad', 'id_almacen'];

    public function salida(): BelongsTo
    {
        return $this->belongsTo(Salida::class, 'id_salida', 'id_salida');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'id_material', 'id_material');
    }

    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }
}
