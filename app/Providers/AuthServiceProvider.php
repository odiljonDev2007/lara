<?php

namespace Vanguard\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Vanguard\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'Vanguard\Model' => 'Vanguard\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     */
    public function boot(): void
    {
        \Blade::directive('role', function ($expression) {
            return "<?php if (\\Auth::user()->hasRole({$expression})) : ?>";
        });

        \Blade::directive('endrole', function ($expression) {
            return '<?php endif; ?>';
        });

        \Blade::directive('permission', function ($expression) {
            return "<?php if (\\Auth::user()->hasPermission({$expression})) : ?>";
        });

        \Blade::directive('endpermission', function ($expression) {
            return '<?php endif; ?>';
        });

        \Gate::define('manage-session', function (User $user, $session) {
            if ($user->hasPermission('users.manage')) {
                return true;
            }

            return (int) $user->id === (int) $session->user_id;
        });
    }
}
