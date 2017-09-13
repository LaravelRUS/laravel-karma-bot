<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 14.10.2015 11:21
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Interfaces\Http\Controllers\Api;

use Domains\Achieve;
use Illuminate\Support\Collection;
use Interfaces\Http\Controllers\Controller;
use Domains\Bot\AchieveSubscriber;
use Interfaces\Gitter\Achieve\AbstractAchieve;

/**
 * Class AchievementsController
 */
class AchievementsController extends Controller
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return \Cache::remember('achievements', 10, function () {
            $achieveStorage = [];

            (new Achieve())
                ->selectRaw('name, count(user_id) as count')
                ->groupBy('name')
                ->get()
                ->each(function ($item) use (&$achieveStorage) {
                    $achieveStorage[$item->name] = $item->count;
                });

            return (new AchieveSubscriber())
                ->toCollection()
                ->each(function (AbstractAchieve $achieve) use ($achieveStorage) {
                    $achieve->users = $achieveStorage[$achieve->name] ?? 0;
                })
                ->toArray();
        });
    }
}