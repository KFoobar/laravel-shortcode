<?php

namespace KFoobar\Shortcode;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

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
        Blade::directive('shortcode', function (string $expression) {
            return "<?php echo Shortcode::render($expression); ?>";
        });

        Collection::macro('withShortcode', function () {
            return $this->map(function ($item) {
                if ($item instanceof Model && method_exists($item, 'withShortcode')) {
                    return $item->withShortcode();
                }

                return $item;
            });
        });

        $this->publishes([
            __DIR__ . '/../config/shortcode.php' => config_path('shortcode.php'),
        ], 'shortcode-config');
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
