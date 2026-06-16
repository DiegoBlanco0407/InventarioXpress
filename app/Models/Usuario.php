<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;
    protected $fillable = ['nombre', 'rol', 'email', 'password_hash', 'ruta_imagen'];

    protected $hidden = ['password_hash'];

    // Sanctum espera el campo 'password' por defecto
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function rolRef(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol', 'id_rol');
    }
}
