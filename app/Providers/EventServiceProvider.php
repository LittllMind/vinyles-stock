<?php

namespace App\Providers;

use App\Models\Bougie;
use App\Models\MouvementStock;
use App\Observers\BougieObserver;
use App\Observers\MouvementStockObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        //
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Bougie::observe(BougieObserver::class);
        MouvementStock::observe(MouvementStockObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
