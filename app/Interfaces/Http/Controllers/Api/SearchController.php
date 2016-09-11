<?php
namespace Interfaces\Http\Controllers\Api;

use Domains\User;
use Illuminate\Http\Request;
use Interfaces\Http\Controllers\Controller;

/**
 * Class SearchController
 * @package Interfaces\Http\Controllers\Api
 */
class SearchController extends Controller
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