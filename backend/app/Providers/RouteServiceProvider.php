<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * L'espace de noms pour les contrôleurs de l'application.
     *
     * @var string|null
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Démarrer les services liés aux routes.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Définir les routes pour l'application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Définir les routes "web" pour l'application.
     *
     * Ces routes reçoivent toutes l'état de session, la protection CSRF, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Définir les routes "api" pour l'application.
     *
     * Ces routes sont généralement sans état.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
