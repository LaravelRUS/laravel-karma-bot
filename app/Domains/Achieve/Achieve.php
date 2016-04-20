<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 19.04.2016 3:14
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Achieve;

use Core\Mappers\Achieve\AchieveMapper;

/**
 * Class Achieve
 * @package Domains\Karma
 */
class Achieve extends AchieveMapper
{
    /**
     * Achieve constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        parent::__construct($attributes);

        if (!$attributes['name']) {
            $attributes['name'] = basename(static::class);
        }
    }
}