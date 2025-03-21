<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Les écouteurs d'événements pour l'application.
     *
     * @var array
     */
    protected $listen = [
        // 'App\Events\SomeEvent' => [
        //     'App\Listeners\EventListener',
        // ],
    ];

    /**
     * Démarrer les services liés aux événements.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
