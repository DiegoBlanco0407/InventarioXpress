<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ciudad extends Model
{
    protected $table = 'ciudades';
    protected $primaryKey = 'id_ciudad';
    public $timestamps = false;
    protected $fillable = ['nombre'];

    public function almacenes(): HasMany
    {
        return $this->hasMany(Almacen::class, 'ciudad', 'id_ciudad');
    }

    public function calles(): HasMany
    {
        return $this->hasMany(Calle::class, 'id_ciudad', 'id_ciudad');
    }
}
