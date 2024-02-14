<?php

namespace App\Providers;

use DI\ContainerBuilder;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Maestro\Manager;
use Monolog\Logger;
use Orkester\GraphQL\GraphQLConfiguration;
use Orkester\Persistence\DatabaseConfiguration;
use Orkester\Persistence\PersistenceManager;
use PDO;

class OrkesterServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
//        Log::withContext([
//            'request-addr' => $_SERVER['REMOTE_ADDR']
//        ]);

        PersistenceManager::init(
            $this->app->get('db'),
            Log::channel(env('LOG_DB_CHANNEL'))
        );

        DB::enableQueryLog();
        DB::listen(function ($query) {
            debugQuery($query->sql, $query->bindings);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::addExtension('js','php');
//        Blade::anonymousComponentPath(app_path("UI/Components"), 'wt');
//        Blade::anonymousComponentPath(app_path("UI/Layouts"), 'wt');
        //Blade::anonymousComponentPath(app_path("UI/Views"), 'wt');
    }
}
