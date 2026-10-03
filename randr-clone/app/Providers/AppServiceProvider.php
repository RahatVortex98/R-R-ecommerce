<?php
namespace App\Providers;
use App\Models\{Category, Page};
use App\Services\CartService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void { $this->app->singleton(CartService::class); }

    public function boot(): void
    {
        View::composer('layouts.shop', function ($v) {
            $v->with([
                'menu' => Category::with('children')->whereNull('parent_id')->where('is_active', true)->orderBy('sort')->get(),
                'footerPages' => Page::where('is_active', true)->get()->groupBy('section'),
                'cartCount' => app(CartService::class)->count(),
            ]);
        });
    }
}