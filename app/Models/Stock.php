<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    protected $table = 'stock';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null; // clave compuesta (id_almacen, id_material)
    protected $fillable = ['id_almacen', 'id_material', 'cantidad', 'necesidades', 'pedir'];

    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'id_material', 'id_material');
    }
}
