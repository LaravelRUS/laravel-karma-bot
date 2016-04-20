<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 16:24
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Services;
use Ramsey\Uuid\Uuid;

/**
 * Class Gitter
 * @package Domains\Services
 */
class Gitter extends Service
{
    /**
     * @return string
     */
    public static function getName()
    {
        return 'gitter';
    }
}