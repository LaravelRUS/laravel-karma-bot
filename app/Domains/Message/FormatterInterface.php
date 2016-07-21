<?php

/**
 * This file is part of GitterBot package.
 *
 * @author butschster <butschster@gmail.com>
 * @date   21.07.2016 10:00
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Message;

interface FormatterInterface
{
    /**
     * @param string $text
     *
     * @return string
     */
    public function answer($text);

    /**
     * @param string $text
     *
     * @return string
     */
    public function pre($text);

    /**
     * @param string $code
     * @param string $lang
     *
     * @return string
     */
    public function code($code, $lang = '');

    /**
     * @param string $text
     *
     * @return string
     */
    public function italic($text);

    /**
     * @param string $text
     *
     * @return string
     */
    public function bold($text);
}
