<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        // passport 令牌的时间设置
        Passport::tokensExpireIn(now()->addMinutes(120));  // 2个小时的令牌过期时间
        Passport::refreshTokensExpireIn(now()->addDays(30));  // 30天的刷新令牌过期时间
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
        
        // 私人访问令牌
        Passport::personalAccessClientId('1');

        //
    }
}
