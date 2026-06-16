<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tramo extends Model
{
    protected $table = 'tramos';
    protected $primaryKey = 'id_tramo';
    public $timestamps = false;
    protected $fillable = ['nombre'];

    public function tramoCalles(): HasMany
    {
        return $this->hasMany(TramoCalle::class, 'id_tramo', 'id_tramo');
    }

    public function instalaciones(): HasMany
    {
        return $this->hasMany(Instalacion::class, 'id_tramo', 'id_tramo');
    }
}
