<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyController;

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
    public function boot()
    {
        Schema::defaultStringLength(191);

        $permission_dep = DashboardController::getDepartmentPermission();
        if ( ! $permission_dep ) {
            $permission_dep = array();
        }
        $moduli = CompanyController::getModules();

        view()->share('permission_dep', $permission_dep);
        view()->share('moduli', $moduli);

       	Carbon::serializeUsing(function ($carbon) {
            return $carbon->format('U');
        });
    }
}