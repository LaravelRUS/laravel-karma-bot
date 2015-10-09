<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 09.10.2015 20:15
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App;

/**
 * Class Karma
 * @package App
 */
class Karma extends \Eloquent
{
    const STATUS_INCREMENT = 'inc';
    const STATUS_DECREMENT = 'dec';

    /**
     * @var string
     */
    protected $table = 'karma';

    /**
     * @var array
     */
    public $timestamps = ['created_at'];

    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at'];
}
