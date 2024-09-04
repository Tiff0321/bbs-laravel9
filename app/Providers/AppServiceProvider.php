<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Nette\Utils\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
		\App\Models\User::observe(\App\Observers\UserObserver::class);
		\App\Models\Reply::observe(\App\Observers\ReplyObserver::class);
		\App\Models\Topic::observe(\App\Observers\TopicObserver::class);

        // 设置 Paginator 的默认风格是 Bootstrap 风格
        \Illuminate\Pagination\Paginator::useBootstrap();

        // 在视图间共享用户数据
        if ($this->app->isLocal()) {
            View::composer('layouts.app', function ($view) {
                $view->with('users', User::all());
            });
        }
    }
}
