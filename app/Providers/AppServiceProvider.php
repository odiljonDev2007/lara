<?php

namespace Vanguard\Providers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;
use Vanguard\Repositories\Country\CountryRepository;
use Vanguard\Repositories\Country\EloquentCountry;
use Vanguard\Repositories\Permission\EloquentPermission;
use Vanguard\Repositories\Permission\PermissionRepository;
use Vanguard\Repositories\Role\EloquentRole;
use Vanguard\Repositories\Role\RoleRepository;
use Vanguard\Repositories\Session\DbSession;
use Vanguard\Repositories\Session\SessionRepository;
use Vanguard\Repositories\User\EloquentUser;
use Vanguard\Repositories\User\UserRepository;
use Vanguard\Repositories\GuideTransport\EloquentGuideTransport;
use Vanguard\Repositories\GuideTransport\GuideTransportRepository;
use Vanguard\Repositories\FinanceDelivery\EloquentFinanceDelivery;
use Vanguard\Repositories\FinanceDelivery\FinanceDeliveryRepository;
use Vanguard\Repositories\PbxApi\EloquentPbxApi;
use Vanguard\Repositories\PbxApi\PbxApiRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale(config('app.locale'));
        config(['app.name' => setting('app_name')]);
        \Illuminate\Database\Schema\Builder::defaultStringLength(191);

        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Database\Factories\\'.class_basename($modelName).'Factory';
        });

        \Illuminate\Pagination\Paginator::useBootstrap();
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserRepository::class, EloquentUser::class);
        $this->app->singleton(RoleRepository::class, EloquentRole::class);
        $this->app->singleton(PermissionRepository::class, EloquentPermission::class);
        $this->app->singleton(SessionRepository::class, DbSession::class);
        $this->app->singleton(CountryRepository::class, EloquentCountry::class);
        $this->app->singleton(GuideTransportRepository::class, EloquentGuideTransport::class);
        $this->app->singleton(FinanceDeliveryRepository::class, EloquentFinanceDelivery::class);
        $this->app->singleton(PbxApiRepository::class, EloquentPbxApi::class);

        if ($this->app->environment('local')) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
