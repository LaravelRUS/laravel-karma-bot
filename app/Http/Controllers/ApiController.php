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
namespace App\Http\Controllers;

use App\Karma;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Response;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getUsersTop()
    {
        return \Cache::remember('top.karma', 1, function() {
            $karmaStorage = [];

            $karma = (new Karma())
                ->selectRaw('user_target_id, count(*) as count')
                ->groupBy('user_target_id')
                ->orderBy('count', 'desc')
                ->take(10)
                ->get()
                ->each(function ($item) use (&$karmaStorage) {
                    $karmaStorage[$item->user_target_id] = $item->count;
                });


            return (new User())
                ->whereIn('id', $karma->pluck('user_target_id'))
                ->get(['id', 'login', 'name', 'gitter_id', 'avatar', 'url'])
                ->each(function (User $user) use ($karmaStorage) {
                    $user->karma_count = $karmaStorage[$user->id];
                });
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getUsers()
    {
        return User::get(['id', 'login', 'name', 'gitter_id', 'avatar', 'url']);
    }

    /**
     * @return User
     */
    public function getUser($gitterId)
    {
        $formatRelations = function(HasMany $query) {
            return $query->orderBy('created_at', 'desc');
        };

        return User::query()
            ->with([
                'karma'         => $formatRelations,
                'thanks'        => $formatRelations,
                'achievements'  => $formatRelations
            ])
            ->where('gitter_id', $gitterId)
            ->first();
    }
}