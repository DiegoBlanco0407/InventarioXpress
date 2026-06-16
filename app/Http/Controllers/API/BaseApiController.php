<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseApiController extends Controller
{
    protected function success(mixed $data = null, string $message = 'Operación realizada correctamente', int $code = 200): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'mensaje' => $message,
            'datos' => $data,
        ], $code);
    }

    protected function error(string $message = 'Ha ocurrido un error', int $code = 400, mixed $errors = null): JsonResponse
    {
        return response()->json([
            'ok' => false,
            'mensaje' => $message,
            'errores' => $errors,
        ], $code);
    }
}
