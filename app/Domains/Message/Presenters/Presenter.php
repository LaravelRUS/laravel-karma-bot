<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 15.04.2016 16:27
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Message\Presenters;

use Domains\Message\Message;
use Domains\Message\Text;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Presenter
 * @package Domains\Message\Presenters
 */
abstract class Presenter implements Renderable
{
    /**
     * @var array
     */
    protected static $tags = [
        'bot',
        'selection',
        'strong',
        'code',
        'removed',
        'list',
        'title',
        'subtitle',
        'quote',
        'link',
        'image',
    ];

    /**
     * @param Message $message
     * @return static
     */
    final public static function createFromMessage(Message $message)
    {
        return static::createFromText($message->text);
    }

    /**
     * @param Text $text
     * @return static
     */
    final public static function createFromText(Text $text)
    {
        return static::createFromString($text->toString());
    }

    /**
     * @param string $string
     * @return static
     */
    final public static function createFromString(string $string)
    {
        return new static($string);
    }

    protected function getUnclosedTags(string $string)
    {
        // TODO
    }

    /**
     * Presenter constructor.
     * @param string $text
     */
    abstract public function __construct(string $text);

    /**
     * @return string
     */
    abstract public function render() : string;
}
