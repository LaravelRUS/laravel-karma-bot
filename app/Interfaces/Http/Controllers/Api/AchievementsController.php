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
use Domains\User\User;
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
     * @return \Illuminate\Database\Eloquent\Collection|Achieve[]
     */
    public function index()
    {
        /** @var Collection $achievements */
        $achievements = Achieve::query()
            ->withCount('user')
            ->groupBy('name')
            ->get();

        return (new AchieveSubscriber())
            ->toCollection()
            ->each(function(AbstractAchieve $achieve) use ($achievements) {
                $found = $achievements->where('name', $achieve->name)->first();
                $achieve->user_count = $found ? $found->user_count : 0;
            })
            ->toArray();
    }

    /**
     * @param $name
     * @return \Illuminate\Database\Eloquent\Collection|User[]
     */
    public function users($name)
    {
        return User::query()
            ->with('achievements')
            ->where('achievements.name', $name)
            ->get();
    }
}