<?php

namespace KFoobar\Shortcode\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use KFoobar\Shortcode\View\ViewFactory;

class ApplyShortcode
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * The middleware should ignore ajax request.
     *
     * @var bool
     */
    protected $ignoreAjax = true;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  array|null  $args
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Routing\Exceptions\InvalidSignatureException
     */
    public function handle($request, Closure $next, ...$args)
    {
        if (!$this->isReading($request) || $this->isRunningTests() || $this->inExceptArray($request)) {
            return $next($request);
        } elseif ($this->isAjax($request) || $this->isNova($request)) {
            return $next($request);
        }

        $this->app->bind(Factory::class, function ($app) {
            return new ViewFactory($app['view.engine.resolver'], $app['view.finder'], $app['events'], $app['config']['view']);
        });

        return $next($request);
    }


    /**
     * Determine if the HTTP request is ajax.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isAjax($request)
    {
        return ($request->ajax() && $this->ignoreAjax === true) ? true : false;
    }

    /**
     * Determine if the HTTP request is from Nova.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isNova($request)
    {
        $paths = array_filter([
            ltrim(trim(config('nova.path'), '/'), '/'),
            'nova-api',
        ]);

        foreach ($paths as $path) {
            if ($request->is($path . '*')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the HTTP request uses a ‘read’ verb.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isReading($request)
    {
        return in_array($request->method(), ['HEAD', 'GET', 'OPTIONS']);
    }

    /**
     * Determine if the application is running unit tests.
     *
     * @return bool
     */
    protected function isRunningTests()
    {
        return $this->app->runningInConsole() || $this->app->runningUnitTests();
    }

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
