<?php

namespace Mprince\Markdown\Drivers;

interface MarkdownDriver
{
    /**
     * Parses a markdown string to HTML.
     * 
     * @param  string  $text
     * @return string
     */
    function text($text);

    /**
     * Parses a single line of markdown to HTML.
     *
     * @param  string  $text
     * @return string
     */
    function line($text);
}