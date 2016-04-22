<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 22.04.2016 16:59
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Repositories\Support;

use Core\Repositories\Support\Eloquent\Transaction;

/**
 * Class Eloquent
 * @package Core\Repositories\Support
 */
trait Eloquent
{
    use Transaction;
}