<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\AlmacenRepositoryInterface;
use App\Repositories\Eloquent\AlmacenRepository;
use App\Repositories\Contracts\MaterialRepositoryInterface;
use App\Repositories\Eloquent\MaterialRepository;
use App\Repositories\Contracts\PedidoRepositoryInterface;
use App\Repositories\Eloquent\PedidoRepository;
use App\Repositories\Contracts\StockRepositoryInterface;
use App\Repositories\Eloquent\StockRepository;
use App\Repositories\Contracts\SalidaRepositoryInterface;
use App\Repositories\Eloquent\SalidaRepository;
use App\Repositories\Contracts\InstalacionRepositoryInterface;
use App\Repositories\Eloquent\InstalacionRepository;
use App\Repositories\Contracts\CalleRepositoryInterface;
use App\Repositories\Eloquent\CalleRepository;
use App\Repositories\Contracts\CiudadRepositoryInterface;
use App\Repositories\Eloquent\CiudadRepository;
use App\Repositories\Contracts\TramoRepositoryInterface;
use App\Repositories\Eloquent\TramoRepository;
use App\Repositories\Contracts\TramoCalleRepositoryInterface;
use App\Repositories\Eloquent\TramoCalleRepository;
use App\Repositories\Contracts\UsuarioRepositoryInterface;
use App\Repositories\Eloquent\UsuarioRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AlmacenRepositoryInterface::class, AlmacenRepository::class);
        $this->app->bind(MaterialRepositoryInterface::class, MaterialRepository::class);
        $this->app->bind(PedidoRepositoryInterface::class, PedidoRepository::class);
        $this->app->bind(StockRepositoryInterface::class, StockRepository::class);
        $this->app->bind(SalidaRepositoryInterface::class, SalidaRepository::class);
        $this->app->bind(InstalacionRepositoryInterface::class, InstalacionRepository::class);
        $this->app->bind(CalleRepositoryInterface::class, CalleRepository::class);
        $this->app->bind(CiudadRepositoryInterface::class, CiudadRepository::class);
        $this->app->bind(TramoRepositoryInterface::class, TramoRepository::class);
        $this->app->bind(TramoCalleRepositoryInterface::class, TramoCalleRepository::class);
        $this->app->bind(UsuarioRepositoryInterface::class, UsuarioRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

