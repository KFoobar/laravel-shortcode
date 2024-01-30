<?php

namespace KFoobar\Shortcode\Macros;

use Illuminate\Database\Eloquent\Model;

/**
 * Enable shortcode on all models in the collection.
 *
 * @mixin \Illuminate\Support\Collection
 *
 * @return mixed
 */
class WithShortcode
{
    public function __invoke()
    {
        return function () {
            return $this->map(function ($item) {
                if ($item instanceof Model && method_exists($item, 'withShortcode')) {
                    return $item->withShortcode();
                }

                return $item;
            });
        };
    }
}
