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
namespace App\Http\Controllers\Api;

use App\Achieve;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Subscribers\AchieveSubscriber;
use App\Gitter\Achieve\AbstractAchieve;

/**
 * Class AchievementsController
 * @package App\Http\Controllers
 */
class AchievementsController extends Controller
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return \Cache::remember('achievements', 10, function() {
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