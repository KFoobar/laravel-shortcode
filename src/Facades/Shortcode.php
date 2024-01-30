<?php

namespace KFoobar\Shortcode\Facades;

use Illuminate\Support\Facades\Facade;

class Shortcode extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \KFoobar\Shortcode\ShortcodeManager::class;
    }
}
