<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Instalacion extends Model
{
    protected $table = 'instalaciones';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null; // (id_tramo, id_material)
    protected $fillable = ['id_tramo', 'id_tramo_calle', 'id_material', 'instalado', 'a_instalar', 'id_almacen'];
    protected $appends = ['tramo_calle_label'];

    public function tramo(): BelongsTo
    {
        return $this->belongsTo(Tramo::class, 'id_tramo', 'id_tramo');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'id_material', 'id_material');
    }

    public function tramoCalle(): BelongsTo
    {
        return $this->belongsTo(TramoCalle::class, 'id_tramo_calle', 'id_tramo_calle');
    }

    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }

    // Accessor para exponer "Tramo (Calle)" directamente en el JSON
    public function getTramoCalleLabelAttribute(): string
    {
        $tramo = $this->tramo ? $this->tramo->nombre : 'Tramo';
        $calle = ($this->tramoCalle && $this->tramoCalle->calle)
            ? $this->tramoCalle->calle->nombre
            : (($this->tramoCalleByTramo && $this->tramoCalleByTramo->calle)
                ? $this->tramoCalleByTramo->calle->nombre
                : 'Calle');
        return sprintf('%s (%s)', $tramo, $calle);
    }

    // Fallback: obtener una relación tramo-calle por id_tramo cuando no se guarda id_tramo_calle
    public function tramoCalleByTramo(): BelongsTo
    {
        return $this->belongsTo(TramoCalle::class, 'id_tramo', 'id_tramo');
    }
}
