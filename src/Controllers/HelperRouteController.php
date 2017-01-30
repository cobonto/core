<?php
/**
 * Created by PhpStorm.
 * User: fara
 * Date: 1/13/2017
 * Time: 3:17 PM
 */

namespace Cobonto\Controllers;


use App\Http\Controllers\Controller;

class HelperRouteController extends Controller
{
    public function urlChanged()
    {
        return transTpl('url_changed');
    }
}