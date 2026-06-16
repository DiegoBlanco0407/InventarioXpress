<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    /**
     * Listado de roles.
     */
    public function index(): JsonResponse
    {
        $roles = Role::select(['id_rol','nombre','descripcion'])->orderBy('id_rol')->get();
        return response()->json([
            'datos' => $roles,
        ]);
    }
}
