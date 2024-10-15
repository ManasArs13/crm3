<?php

namespace App\Providers;

use App\Models\Contact;
use App\Models\Order;
use App\Models\OrderAmo;
use App\Models\Product;
use App\Models\ShipmentProduct;
use App\Models\TechProcess;
use App\Observers\OrderAmoObserver;
use App\Observers\OrderMsObserver;
use App\Observers\Production\ProcessingObserer;
use App\Observers\ProductObserver;
use App\Observers\Shipment\ShipmentProductObserver;
use App\Services\Entity\ContactMsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $observers = [
        Order::class => [OrderMsObserver::class],
        OrderAmo::class => [OrderAmoObserver::class],
        Product::class => [ProductObserver::class],
        ShipmentProduct::class => [ShipmentProductObserver::class]
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Order::observe(OrderMsObserver::class);
        OrderAmo::observe(OrderAmoObserver::class);
        Product::observe(ProductObserver::class);
        Contact::observe(ContactMsService::class);
        TechProcess::observe(ProcessingObserer::class);
        ShipmentProduct::observe(ShipmentProductObserver::class);

        // \URL::forceRootUrl(\Config::get('app.url'));
        // if (str_contains(\Config::get('app.url'), 'https://')) {
        //     \URL::forceScheme('https');
        // }
    }
}
