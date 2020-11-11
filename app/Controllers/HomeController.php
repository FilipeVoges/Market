<?php


namespace App\Controllers;

use App\Modules\Configuration\View;

/**
 * Class HomeController
 * @package App\Controllers
 */
class HomeController extends Controller
{
    public static function home() {
        $view = new View('home.twig');

        return $view->render();
    }

}