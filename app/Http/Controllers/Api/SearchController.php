<?php
/**
 * This file is part of GitterBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Http\Controllers\Api;

use App\Domains\User\User;
use Illuminate\Http\Request;

/**
 * Class SearchController
 * @package App\Http\Controllers\Api
 */
class SearchController
{
    /**
     * @param Request $request
     * @return array
     */
    public function users(Request $request)
    {
        if (!trim($request->get('query'))) {
            return [];
        }

        return User::with('achievements')
            ->withCount('karma', 'thanks')
            ->where('name', 'LIKE', '%' . $request->get('query') . '%')
            ->orWhere('login', 'LIKE', '%' . $request->get('query') . '%')
            ->orderBy('karma_count', 'desc')
            ->paginate(12);
    }
}