<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 18.04.2016 13:54
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Presenters;

/**
 * Class PlainTextPresenter
 * @package Core\Presenters
 */
class PlainTextPresenter implements Presenter
{
    /**
     * @param string $source
     * @return string
     */
    public static function encode(string $source) : string
    {
        $tags = Tags::all();
        $pattern = sprintf('/<(?:/)?%s>/isu', implode('|', $tags));

        return preg_replace($pattern, '', $source);
    }

    /**
     * @param string $text
     * @return string
     */
    public static function decode(string $text) : string
    {
        return $text;
    }
}
