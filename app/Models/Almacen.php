<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Almacen extends Model
{
    // Tabla y clave primaria personalizadas
    protected $table = 'almacenes';
    protected $primaryKey = 'id_almacen';
    public $timestamps = false;
    protected $fillable = ['nombre', 'id_ciudad'];

    // Relaciones
    public function ciudadRef(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad', 'id_ciudad');
    }

    public function stock(): HasMany
    {
        return $this->hasMany(Stock::class, 'id_almacen', 'id_almacen');
    }
}
