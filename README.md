# Shortcodes for Laravel

Efficient and versatile shortcode manager tailored for Laravel versions 8, 9, and 10.

## Installation

Install the package using Composer:

```bash
composer require kfoobar/laravel-shortcode
```

## Settings

Publish the configuration file to customize settings:

```bash
php artisan vendor:publish --tag=shortcode-config
```

## Usage

### Adding Custom Shortcodes

Easily define custom shortcodes like this:

```php
Shortcode::add('author', 'Joe Doe');
```

### Configuring Default Shortcodes

Use the shortcodes array in the config file to set default shortcodes:

```php
'shortcodes' => [
    'author' => 'John Doe',
],
```

### Shortcode Formatting

Shortcode keys are automatically transformed: they are converted to uppercase and wrapped with a character defined in the `wrapper` setting of your config file. The default wrapper character is `%`.

### Rendering Shortcodes

btain parsed content with various options:

```php
// Standard Parsing
Shortcode::render($content);

// Markdown Conversion
Shortcode::markdown($content);

// Text Parsing (Stripping HTML)
Shortcode::text($content);
```

```php
Shortcode::render('%YEAR% will be awesome!'); // 2024 will be awesome!
```

Within Blade templates, use the `@shortcode` directive:

```blade
@shortcode($content)
```

*Note: The Blade directive employs the render() method.*

## Integrating with Models

Utilize the `HasShortcode` trait for automatic shortcode parsing:

```php
use KFoobar\Shortcode\Traits\HasShortcode;

class MyModel extends Model
{
    use HasShortcode;

    // Specify attributes for parsing or use '*' for all
    protected $shortcodes = ['*'];
```

Automatic shortcode parsing is turned off by default to avoid conflicts with writing operations. 
This precaution ensures that routine create, update and delete actions proceed without unintentional 
interference from the shortcode processing mechanism.

### Enable With Code

```php
$model = (new MyModel)->withShortcode();
```

### Enable With Model Setting

Enable auto-parsing by default:

```php
protected $shortcode = true;
```

### Enable With Middleware

Enable auto-parsing for specific routes:

```php
protected $middlewareGroups = [
    'web' => [
        // other middleware
        \KFoobar\Shortcode\Middlware\ApplyShortcode::class,
    ],
```

*The middleware automatically excludes non-read requests, AJAX requests, and Laravel Nova requests.*

## Predefined Shortcodes

These are the predefined shortcodes:

| Shortcode  | Value               |
| ---------- | ------------------- |
| %YEAR%     | 2024                |
| %MONTH%    | January             |
| %WEEK%     | 3                   |
| %DAY%      | Monday              |
| %DATE%     | 2024-01-01          |
| %TIME%     | 12:00               |
| %DOMAIN%   | project.test        |
| %APP-NAME% | Laravel             |
| %APP-URL%  | http://project.test |

## Contributing

Your contributions are highly appreciated.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
