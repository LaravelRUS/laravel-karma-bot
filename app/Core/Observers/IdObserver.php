<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 13:44
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Observers;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Class IdObserver
 * @package Core\Observers
 */
class IdObserver
{
    /**
     * @param Model $model
     */
    public function creating(Model $model)
    {
        if (!$model->id) {
            $model->id = Uuid::uuid4()->toString();
        }
    }
}