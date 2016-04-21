<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 16:10
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Mappers\Services;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ServiceMapper
 * @package Core\Mappers\Services
 * @property-read string $id
 * @property-read string $service_id
 * @property-read string $name
 */
class ServiceMapper extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $table = 'services';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $casts = [
        'id'         => 'string',
        'service_id' => 'string',
        'name'       => 'string',
    ];

}