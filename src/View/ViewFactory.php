<?php

namespace KFoobar\Shortcode\View;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\View\Factory as FactoryContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Factory;
use Illuminate\View\View;

class ViewFactory extends Factory implements FactoryContract
{
    /**
     * Create a new view instance from the given arguments.
     *
     * @param  string  $view
     * @param  string  $path
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $data
     * @return \Illuminate\Contracts\View\View
     */
    protected function viewInstance($view, $path, $data)
    {
        $data = $this->findModelsInData($data);

        foreach ($data as &$item) {
            if ($item instanceof Model && method_exists($item, 'withShortcode')) {
                $item = $item->withShortcode();
            }
        }

        return new View($this, $this->getEngineFromPath($path), $view, $path, $data);
    }

    /**
     * Finds all models in data.
     *
     * @param array $data
     * @return mixed
     */
    protected function findModelsInData(&$data = []): mixed
    {
        if (is_array($data)) {
            foreach ($data as $item) {
                $this->findModelsInData($item);
            }
        } elseif ($data instanceof Paginator) {
            foreach ($data->getCollection() as $item) {
                $this->findModelsInData($item);
            }
        } elseif ($data instanceof Model && method_exists($data, 'withShortcode')) {
            $data = $data->withShortcode();
        }

        return $data;
    }
}
