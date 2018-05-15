<?php

namespace App\Http\Controllers\Open;

use App\Helpers\RouteHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Handles application landing pages (eg: homepage)
 */
class WelcomeController extends Controller
{
    /**
     * Show the application homepage
     *
     * @return Response
     */
    public function show()
    {
        return view('open.home');
    }
}
