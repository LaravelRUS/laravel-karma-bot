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

namespace Interfaces\Gitter;

use Domains\Room\AbstractRoom;

class StandartGitterRoom extends AbstractRoom
{
    /**
     * AbstractRoom constructor.
     *
     * @param string $id
     * @param string $alias
     * @param string|array $groups
     * @param array  $middleware
     */
    public function __construct($id, $alias, $groups = '*', array $middleware = [])
    {
        parent::__construct();

        $this->id = $id;
        $this->alias = $alias;
        $this->groups = (array) $groups;

        $this->setMiddleware($middleware);
    }

    /**
     * @return string
     */
    public function driver()
    {
        return 'gitter';
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function answer($text)
    {
        $this->sendMessage($text);

        return $text;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function pre($text)
    {
        return $this->answer($this->decorate('`', $text));
    }

    /**
     * @param string $code
     * @param string $lang
     *
     * @return string
     */
    public function code($code, $lang = '')
    {
        return $this->answer(
            '```' . $lang . "\n" . $code . "\n" . '```'
        );
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function italic($text)
    {
        return $this->answer($this->decorate('_', $text));
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function bold($text)
    {
        return $this->answer($this->decorate('**', $text));
    }

    /**
     * @param $symbol
     * @param $text
     * @return string
     */
    protected function decorate($symbol, $text)
    {
        $result = [];
        $strings = explode("\n", $text);
        foreach ($strings as $string) {
            $result[] =
                $symbol .
                str_replace($symbol, '\\' . $symbol, trim($string)) .
                $symbol;
        }

        return implode("\n", $result);
    }
}