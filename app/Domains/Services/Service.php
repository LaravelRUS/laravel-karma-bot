<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 16:08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Services;

use Core\Mappers\Services\ServiceMapper;
use Ramsey\Uuid\Uuid;

/**
 * Class Service
 * @package Domains\Services
 */
class Service extends ServiceMapper
{
    /**
     * Gitter constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $attributes['name'] = $attributes['name'] ?? static::getName();

        parent::__construct($attributes);
    }

    /**
     * @return null
     */
    public static function getName()
    {
        return null;
    }
}