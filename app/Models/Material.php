<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    protected $table = 'materiales';
    protected $primaryKey = 'id_material';
    public $timestamps = false;
    protected $fillable = ['concepto'];

    public function pedidoDetalles(): HasMany
    {
        return $this->hasMany(PedidoDetalle::class, 'id_material', 'id_material');
    }

    public function stock(): HasMany
    {
        return $this->hasMany(Stock::class, 'id_material', 'id_material');
    }

    public function instalaciones(): HasMany
    {
        return $this->hasMany(Instalacion::class, 'id_material', 'id_material');
    }
}
