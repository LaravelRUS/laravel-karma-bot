<?php
/**
 * This file is part of GitterBot package.
 *
 * @author butschster <butschster@gmail.com>
 * @date 20.07.2016 15:34
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Domains\Bot;

/**
 * Class AchieveSubscriber
 */
interface TextParserInterface
{
    /**
     * @param string $message
     *
     * @return string
     */
    public function parse(string $message);
}