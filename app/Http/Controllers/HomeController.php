<?php
/**
 * This file is part of GitterBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Http\Controllers;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('home');
    }
}