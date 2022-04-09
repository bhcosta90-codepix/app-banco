<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('pubsub', config('pubsub.type'));

        if (app()->environment('local')) {
            DB::listen(function ($query) {
                Log::info($this->bindQueryLog($query->sql, $query->bindings));
            });
        }
    }

    private function bindQueryLog($sql, $binds)
    {
        if (empty($binds)) {
            return $sql;
        }

        $sql = str_replace(['%', '?'], ['%%', '\'%s\''], $sql);

        return vsprintf($sql, $binds);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
