<?php

namespace App\Providers;

use App\Models\Contact;
use App\Models\Order;
use App\Models\OrderAmo;
use App\Models\Product;
use App\Observers\OrderAmoObserver;
use App\Observers\OrderMsObserver;
use App\Observers\ProductObserver;
use App\Services\Entity\ContactMsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $observers = [
        Order::class => [OrderMsObserver::class],
        OrderAmo::class => [OrderAmoObserver::class],
        Product::class => [ProductObserver::class],
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

        \URL::forceRootUrl(\Config::get('app.url'));
        if (str_contains(\Config::get('app.url'), 'https://')) {
            \URL::forceScheme('https');
        }
    }
}
