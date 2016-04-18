<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 18.04.2016 13:58
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Presenters;

/**
 * Class Tags
 * @package Core\Presenters
 */
class Tags
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
     * @return array
     */
    public static function all()
    {
        return static::$tags;
    }
}
