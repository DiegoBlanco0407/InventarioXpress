<?php

namespace App\Http\Controllers\API;

use App\Models\Almacen;
use App\Models\Material;
use App\Models\Pedido;
use App\Models\Ciudad;
use App\Models\Instalacion;
use App\Models\Salida;
use Carbon\Carbon;

class StatsController extends BaseApiController
{
    public function dashboard()
    {
        $almacenes = Almacen::query()->count();
        $materiales = Material::query()->count();
        $desde = Carbon::now()->subMonth()->startOfDay();
        $pedidos_mes = Pedido::query()
            ->where('fecha', '>=', $desde->toDateString())
            ->count();

        // Ciudades activas: total de ciudades registradas
        $ciudades_activas = Ciudad::query()->count();

        // Instalaciones por terminar: a_instalar > instalado
        $instalaciones_pendientes = Instalacion::query()
            ->whereColumn('a_instalar', '>', 'instalado')
            ->count();

        // Salidas este mes: desde inicio del mes en curso
        $inicioMes = Carbon::now()->startOfMonth()->toDateString();
        $salidas_mes = Salida::query()
            ->where('fecha', '>=', $inicioMes)
            ->count();

        return $this->success([
            'almacenes' => $almacenes,
            'materiales' => $materiales,
            'pedidos_ultimo_mes' => $pedidos_mes,
            'ciudades_activas' => $ciudades_activas,
            'instalaciones_por_terminar' => $instalaciones_pendientes,
            'salidas_este_mes' => $salidas_mes,
        ], 'Estadísticas de dashboard');
    }
}
