<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salida extends Model
{
    protected $table = 'salidas';
    protected $primaryKey = 'id_salida';
    public $timestamps = false;
    protected $fillable = ['fecha', 'id_tramo_calle', 'destinacion'];
    protected $appends = ['tramo_calle_label'];

    public function detalles(): HasMany
    {
        return $this->hasMany(SalidaDetalle::class, 'id_salida', 'id_salida');
    }

    public function tramoCalle(): BelongsTo
    {
        return $this->belongsTo(TramoCalle::class, 'id_tramo_calle', 'id_tramo_calle');
    }

    public function getTramoCalleLabelAttribute(): string
    {
        $tramo = ($this->tramoCalle && $this->tramoCalle->tramo) ? $this->tramoCalle->tramo->nombre : 'Tramo';
        $calle = ($this->tramoCalle && $this->tramoCalle->calle) ? $this->tramoCalle->calle->nombre : 'Calle';
        return sprintf('%s (%s)', $tramo, $calle);
    }

    /**
     * Boot method to handle cascade deletion
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($salida) {
            // Eliminar todos los detalles asociados antes de eliminar la salida
            $salida->detalles()->delete();
        });
    }
}
