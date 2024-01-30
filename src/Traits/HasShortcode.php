<?php

namespace KFoobar\Shortcode\Traits;

use KFoobar\Shortcode\Facades\Shortcode;

trait HasShortcode
{
    /**
     * This enables auto parsing for shortcodes.
     *
     * @var bool
     */
    protected $shortcode = false;

    /**
     * The attributes that are parseable with shortcodes.
     *
     * @var array<string>|bool
     */
    protected $shortcodes = ['*'];

    /**
     * Determine if model should return attributes with shortcodes.
     *
     * @return self
     */
    public function withShortcode()
    {
        $this->shortcode = true;

        return $this;
    }

    /**
     * Determine if models should return attributes without shortcodes.
     *
     * @return self
     */
    public function withoutShortcode()
    {
        $this->shortcode = false;

        return $this;
    }

    /**
     * Get a plain attribute (not a relationship).
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttributeValue($key)
    {
        $value = $this->transformModelValue($key, $this->getAttributeFromArray($key));

        if ($this->shortcode !== true || !is_string($value)) {
            return $value;
        } elseif ($this->shortcodes == ['*']) {
            return $this->getAttributeShortcode($key, $value);
        } elseif (!empty($this->shortcodes) && in_array($key, $this->shortcodes)) {
            return $this->getAttributeShortcode($key, $value);
        }

        return $value;
    }

    /**
     * Gets the attribute shortcode.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return string
     */
    public function getAttributeShortcode($key, $value): string
    {
        return Shortcode::text($value);
    }
}
