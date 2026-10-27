<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
        Carbon::setLocale('ar');

        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        View::composer(['components.quote-card', 'components.share-modal'], function ($view) {
            $view->with('allCategories', once(fn () => Category::orderBy('id')->get()));
            $view->with('allBooks', once(fn () => Book::orderBy('title')->get(['id', 'title', 'author'])));
        });
    }
}
