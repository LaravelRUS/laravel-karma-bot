<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 19.04.2016 2:44
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Achieve\Meta;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Event
 * @package Domains\Achieve\Meta
 * @Annotation
 * @Target("METHOD")
 */
class Event
{
    /**
     * @var mixed
     */
    public $entity = null;

    /**
     * @var string
     */
    public $name;
}