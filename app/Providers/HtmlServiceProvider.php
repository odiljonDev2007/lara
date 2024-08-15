<?php

namespace Vanguard\Providers;

use Collective\Html\FormBuilder;
use Collective\Html\HtmlBuilder;
use Collective\Html\HtmlServiceProvider as BaseHtmlServiceProvider;

class HtmlServiceProvider extends BaseHtmlServiceProvider
{
    protected function registerHtmlBuilder(): void
    {
        $this->app->singleton('html', function ($app) {
            if (config('app.force_ssl')) {
                $app['url']->forceScheme('https');
            }

            return new HtmlBuilder($app['url'], $app['view']);
        });
    }

    protected function registerFormBuilder(): void
    {
        $this->app->singleton('form', function ($app) {
            if (config('app.force_ssl')) {
                $app['url']->forceScheme('https');
            }

            $form = new FormBuilder($app['html'], $app['url'], $app['view'], $app['session.store']->token());

            return $form->setSessionStore($app['session.store']);
        });
    }
}
