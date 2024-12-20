<?php

namespace Webkul\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;

class Locale
{
    /**
     * Application instance.
     * 
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Request instance.
     * 
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The middleware instance.
     * 
     * @param  \Illuminate\Foundation\Application  $app
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(
        Application $app,
        Request $request
    ) {
        $this->app = $app;
        $this->request = $request;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        app()->setLocale(
            core()->getConfigData('general.locale_settings.locale')
                ?: app()->getLocale()
        );

        return $next($request);
    }
}