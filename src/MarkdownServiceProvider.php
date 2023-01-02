<?php

namespace Mprince\Markdown;

use Blade;
use Parsedown;
use Mprince\Markdown\Parser;
use Illuminate\Support\ServiceProvider;
use Mprince\Markdown\Drivers\ParsedownDriver;

class MarkdownServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the Markdown Blade directives and publish
     * the config file.
     * 
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/markdown.php' => config_path('markdown.php'),
        ]);

        Blade::directive('markdown', function($markdown) {
            if ($markdown) {
                return "<?php echo app('Mprince\Markdown\Parser')->parse($markdown); ?>";
            }

            return "<?php app('Mprince\Markdown\Parser')->begin() ?>";
        });

        Blade::directive('endmarkdown', function () {
            return "<?php echo app('Mprince\Markdown\Parser')->end() ?>";
        });
    }

    /**
     * Bind the Markdown facade and the parser class to
     * the container.
     * 
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Parser::class, function ($app) {
            return new Parser(new ParsedownDriver(config('markdown') ?? []));
        });

        $this->app->bind('markdown', Parser::class);
    }
}
