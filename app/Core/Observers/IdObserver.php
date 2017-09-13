<?php
declare(strict_types = 1);
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 28.03.2016 15:52
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Core\Observers;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;

/**
 * Class IdObserver
 * @package Core\Observers
 */
class IdObserver
{
    /**
     * @param Model $model
     */
    public function saving(Model $model)
    {
        if (!$model->id) {
            $model->id = Uuid::uuid4()->toString();
        }
    }
}
