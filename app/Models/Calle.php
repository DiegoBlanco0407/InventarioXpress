<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Calle extends Model
{
    protected $table = 'calles';
    protected $primaryKey = 'id_calle';
    public $timestamps = false;
    protected $fillable = ['nombre', 'id_ciudad'];

    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad', 'id_ciudad');
    }

    public function tramosCalle(): HasMany
    {
        return $this->hasMany(TramoCalle::class, 'id_calle', 'id_calle');
    }
}
