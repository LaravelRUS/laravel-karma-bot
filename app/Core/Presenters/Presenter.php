<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 18.04.2016 13:52
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Presenters;

/**
 * Interface Presenter
 * @package Core\Presenters
 */
interface Presenter
{
    /**
     * Source code with tags to output format
     * @param string $source
     * @return string
     */
    public static function encode(string $source) : string;

    /**
     * Result text to source message format
     * @param string $text
     * @return string
     */
    public static function decode(string $text) : string;
}
