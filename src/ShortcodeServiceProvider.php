<?php

namespace KFoobar\Shortcode;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use KFoobar\Shortcode\Macros\WithShortcode;

class ShortcodeServiceProvider extends BaseServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerBlade();
        $this->registerMacro();

        $this->publishes([
            __DIR__ . '/../config/shortcode.php' => config_path('shortcode.php'),
        ], 'shortcode-config');
    }

    /**
     * Register Blade directives.
     *
     * @return void
     */
    protected function registerBlade(): void
    {
        Blade::directive('shortcode', function ($expression) {
            return "<?php echo \KFoobar\Shortcode\Facades\Shortcode::render($expression); ?>";
        });
    }

    /**
     * Register macros.
     *
     * @return void
     */
    protected function registerMacro(): void
    {
        Collection::macro('withShortcode', resolve(WithShortcode::class)());
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/shortcode.php',
            'shortcode'
        );
    }
}
