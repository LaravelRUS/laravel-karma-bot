<?php
namespace Interfaces\Http\Controllers;

/**
 * Class HomeController
 */
class HomeController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('home');
    }
}