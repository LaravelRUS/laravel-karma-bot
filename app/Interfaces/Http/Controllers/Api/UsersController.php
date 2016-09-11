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

use Domains\User;
use Domains\Karma;
use Illuminate\Http\Request;
use Interfaces\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class UsersController
 */
class UsersController extends Controller
{
    public function index()
    {

    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function top()
    {
        return User::with('achievements')
            ->withCount('karma')
            ->withCount('thanks')
            ->orderBy('karma_count', 'desc')
            ->orderBy('thanks_count', 'desc')
            ->paginate(12);
    }

    public function user()
    {

    }
}
