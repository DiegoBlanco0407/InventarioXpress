<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TramoCalle extends Model
{
    protected $table = 'tramo_calle';
    protected $primaryKey = 'id_tramo_calle';
    public $timestamps = false;
    protected $fillable = ['id_tramo_calle', 'id_tramo', 'id_calle'];

    public function tramo(): BelongsTo
    {
        return $this->belongsTo(Tramo::class, 'id_tramo', 'id_tramo');
    }

    public function calle(): BelongsTo
    {
        return $this->belongsTo(Calle::class, 'id_calle', 'id_calle');
    }
}
