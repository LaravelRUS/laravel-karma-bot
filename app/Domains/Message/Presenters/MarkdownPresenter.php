<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 15.04.2016 20:13
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Message\Presenters;

/**
 * Class MarkdownPresenter
 * @package Domains\Message\Presenters
 */
class MarkdownPresenter extends Presenter
{
    /**
     * @var string
     */
    private $text;

    /**
     * MarkdownPresenter constructor.
     * @param string $text
     */
    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function render() : string
    {
        $tags   = implode('|', static::$tags);

        $result = '';
        $lines  = explode("\n", $this->text);
        foreach ($lines as $line) {
            $tags = $this->getUnclosedTags($line); // TODO

        }
    }
}
