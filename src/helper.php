<?php

if (! function_exists('markdown')) {
    /**
     * Short-hand helper function to parse a
     * markdown string to HTML.
     * 
     * @see \Mprince\Markdown\Parser::parse
     * @param  string  $markdown
     * @return string
     */
    function markdown($markdown)
    {
        return app('Mprince\Markdown\Parser')->parse($markdown);
    }
}

if (! function_exists('markdown_capture')) {
    /**
     * Short-hand helper function to parse
     * all output from a closure from markdown
     * to HTML.
     * 
     * @see \Mprince\Markdown\Parser::begin
     * @see \Mprince\Markdown\Parser::end
     * @param  Closure  $callback
     * @return string
     */
    function markdown_capture(Closure $callback)
    {
        $parser = app('Mprince\Markdown\Parser');
        $parser->begin();
        $callback();
        return $parser->end();
    }
}

