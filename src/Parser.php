<?php

namespace Mprince\Markdown;

use Mprince\Markdown\Drivers\MarkdownDriver;
use Mprince\Markdown\Exceptions\InvalidTagException;

class Parser
{
    /**
     * Indicates if the parser is currently capturing input.
     * 
     * @var boolean
     */
    protected $capturing = false;

    protected $driver;

    /**
     * Create a new Parser object, used for parsing markdown strings to HTML.
     * 
     * @param  \Mprince\Markdown\Drivers\MarkdownDriver  $driver
     */
    public function __construct(MarkdownDriver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Parses a markdown string to HTML.
     * 
     * @param  string  $text
     * @return string
     */
    public function parse($text)
    {
        if (empty($text)) {
            return '';
        }

        return $this->driver->text(
            static::removeLeadingWhitespace($this->escape($text))
        );
    }

    /**
     * Parses a single line of markdown to HTML.
     *
     * @param  string  $text
     * @return string
     */
    public function line($text)
    {
        return $this->driver->line($this->escape($text));
    }

    /**
     * Escape any XSS attempts related to injecting JavaScript in anchor tags.
     * Will only escape the string if the escape option is set to true in the
     * config.
     *
     * @param  string  $text
     * @return string
     */
    public function escape($text)
    {
        if (config('markdown.xss')) {
            return preg_replace('/(\[.*\])\(javascript:.*\)/', '$1(#)', $text);
        }

        return $text;
    }

    /**
     * Start capturing output to be parsed.
     * 
     * @return void
     */
    public function begin()
    {
        $this->capturing = true;

        ob_start();
    }

    /**
     * Stop capturing output, parse the string from markdown to HTML and return
     * it. Throws an exception if outpout capturing hasn't been started yet.
     * 
     * @throws \Mprince\Markdown\Exceptions\InvalidTagException
     * @return string
     */
    public function end()
    {
        if ($this->capturing === false) {
            throw new InvalidTagException(
                "Markdown capturing have not been started."
            );
        }

        $this->capturing = false;

        $text = ob_get_clean();

        return $this->parse($text);
    }

    public function setDriver(MarkdownDriver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Removes indentation according to the indentation of the first line, of
     * the given markdown text. This prevents markdown from being rendered
     * as code in unwanted places.
     * 
     * @param  string  $text
     * @return string
     */
    public static function removeLeadingWhitespace($text)
    {
        $i = 0;

        while (! $firstLine = explode("\n", $text)[$i]) {
            $i++;
        }

        preg_match('/^( *)/', $firstLine, $matches);
        return preg_replace('/^[ ]{'.strlen($matches[1]).'}/m', '', $text);
    }
}