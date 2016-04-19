<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 19.04.2016 3:49
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Achieve;

/**
 * Interface AchieveInterface
 * @package Domains\Achieve
 * @property-read string $type
 * @property-read string $title
 * @property-read string $description
 * @property-read string $image
 */
interface AchieveInterface
{
    const PERMANENT  = 1;
    const CHANGEABLE = 2;
    const SPECIAL    = 3;

    const EVENT_ADD = 'achieve:add';

    /**
     * @return int
     */
    public function getType() : int;

    /**
     * @return string
     */
    public function getTitle() : string;

    /**
     * @return string
     */
    public function getDescription() : string;

    /**
     * @return string
     */
    public function getImage() : string;
}