<?php
/**
 * This file is part of GitterBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Http\Controllers\Api;

use App\Domains\User\User;
use App\Domains\Achieve\Achieve;
use Illuminate\Support\Collection;

/**
 * Class AchievementsController
 * @package App\Http\Controllers\Api
 */
class AchievementsController
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

        return [];
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