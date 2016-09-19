<?php
/**
 * This file is part of GitterBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Http\Controllers\Api;

use App\Domains\User\User;

/**
 * Class UsersController
 * @package App\Http\Controllers\Api
 */
class UsersController
{
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
