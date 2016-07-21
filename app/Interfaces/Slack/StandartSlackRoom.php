<?php
/**
 * This file is part of GitterBot package.
 *
 * @author butschster <butschster@gmail.com>
 * @date   20.07.2016 15:27
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Interfaces\Slack;

use Domains\Room\AbstractRoom;
use Domains\Middleware\Storage;
use Interfaces\Gitter\StandartGitterRoom;

class StandartSlackRoom extends StandartGitterRoom
{

    /**
     * @return string
     */
    public function driver()
    {
        return 'slack';
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function answer($text)
    {
        $text = preg_replace('/\[(.*?)\]\((.*?)\)/', '<$2|$1>', $text);

        $this->sendMessage($text);

        return $text;
    }
}