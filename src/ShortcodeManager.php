<?php

namespace KFoobar\Shortcode;

use Illuminate\Support\Str;

class ShortcodeManager
{
    /**
     * The shortcodes.
     *
     * @var array
     */
    protected $shortcodes = [];

    /**
     * The character that wraps the shortcode key.
     *
     * @var string|null
     */
    protected $wrapper;

    /**
     * Constructs a new instance.
     */
    public function __construct()
    {
        $this->wrapper = config('shortcode.wrapper', '%');

        $this->boot();
    }

    /**
     * Renders the given content.
     *
     * @param mixed $content
     *
     * @return string
     */
    public function render(mixed $content): string
    {
        return str_replace(array_keys($this->shortcodes), array_values($this->shortcodes), $content);
    }

    /**
     * Returns parsed content as Markdown.
     *
     * @param mixed $content
     *
     * @return string
     */
    public function markdown(mixed $content): string
    {
        $content = $this->render($content);

        return Str::markdown($content, config('shortcode.markdown', []));
    }

    /**
     * Returns parsed content with HTML tags.
     *
     * @param mixed $content
     *
     * @return string
     */
    public function text(mixed $content): string
    {
        $content = $this->render($content);

        return strip_tags($content);
    }

    /**
     * Add single shortcode or array of shortcodes.
     *
     * @param array|string $key
     * @param string       $value
     *
     * @return self
     */
    public function add(string|array $key, string $value = null)
    {
        if (is_array($key)) {
            $this->addShortcodes($key);
        } else {
            $this->addShortcode($key, $value);
        }

        return $this;
    }

    /**
     * Adds a shortcode.
     *
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    protected function addShortcode(string $key, string $value): void
    {
        $this->shortcodes = array_merge($this->shortcodes, [
            $this->formatShortcode($key) => $value
        ]);
    }

    /**
     * Adds shortcodes.
     *
     * @param array $shortcodes
     *
     * @return void
     */
    protected function addShortcodes(array $shortcodes): void
    {
        foreach ($shortcodes as $key => $value) {
            $this->addShortcode($key, $value);
        }
    }

    /**
     * Formats and padds the shortcode key.
     *
     * @param string $key
     *
     * @return string
     */
    protected function formatShortcode(string $key): string
    {
        $key = Str::upper($key);

        if (!Str::startsWith($key, $this->wrapper)) {
            $key = sprintf('%s%s', $this->wrapper, $key);
        }

        if (!Str::endsWith($key, $this->wrapper)) {
            $key = sprintf('%s%s', $key, $this->wrapper);
        }

        //Str::wrap($key, $this->wrapper);

        return $key;
    }

    /**
     * Boots the manager.
     *
     * @return void
     */
    protected function boot(): void
    {
        $shortcodes = array_merge(
            $this->getShortcodesFromConfig(),
            $this->getShortcodesFromPackage(),
        );

        $this->addShortcodes($shortcodes);
    }

    /**
     * Gets the shortcodes from configuration.
     *
     * @return array
     */
    protected function getShortcodesFromConfig(): array
    {
        return config('shortcode.shortcodes', []);
    }

    /**
     * Gets the shortcodes from package.
     *
     * @return array
     */
    protected function getShortcodesFromPackage(): array
    {
        return [
            'YEAR'     => date('Y'),
            'MONTH'    => date('F'),
            'WEEK'     => date('W'),
            'DAY'      => date('l'),
            'DATE'     => now()->format(config('shortcode.formats.date', 'Y-m-d')),
            'TIME'     => now()->format(config('shortcode.formats.time', 'H:i')),
            'DOMAIN'   => parse_url(request()->root())['host'] ?? null,
            'APP-NAME' => config('app.name'),
            'APP-URL'  => url('/'),
        ];
    }
}
