<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Wrapper
    |--------------------------------------------------------------------------
    |
    | This value specifies the character or sequence of characters used to
    | enclose your shortcodes. This wrapping mechanism helps to distinguish
    | shortcodes from other text in your documents, ensuring they are
    | correctly identified and processed.
    |
    */

    'wrapper' => '%',

    /*
    |--------------------------------------------------------------------------
    | Custom shortcodes
    |--------------------------------------------------------------------------
    |
    | This value defines your default shortcodes. This flexible feature enables
    | you add your own shortcodes, specifying the unique identifier for each
    | shortcode and its corresponding behavior or output.
    |
    */

    'shortcodes' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Default formats
    |--------------------------------------------------------------------------
    |
    | These values defines the specific patterns for displaying dates and
    | times within your shortcodes.
    |
    */

    'formats' => [

        'date' => 'Y-m-d',
        'time' => 'H:i',

    ],

    /*
    |--------------------------------------------------------------------------
    | Markdown
    |--------------------------------------------------------------------------
    |
    | This setting configures the handling of Markdown content within
    | the package. All configurations specified within the 'markdown' array
    | are passed as options to the League\CommonMark\GithubFlavoredMarkdownConverter.
    | Adjust these settings to fine-tune how your Markdown content is parsed
    | and rendered, ensuring it meets your application's specific needs.
    |
    */

    'markdown' => [

        'html_input' => 'strip',

    ],

];
