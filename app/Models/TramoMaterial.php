<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Nota: Este modelo mapea la tabla 'instalaciones' como relación tramo-material
class TramoMaterial extends Model
{
    protected $table = 'instalaciones';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null;
    protected $fillable = ['id_tramo', 'id_material', 'instalado', 'a_instalar'];

    public function tramo(): BelongsTo
    {
        return $this->belongsTo(Tramo::class, 'id_tramo', 'id_tramos');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'id_material', 'id_material');
    }
}
