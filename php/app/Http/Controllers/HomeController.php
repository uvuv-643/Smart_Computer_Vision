<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use HomeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index(HomeService $service): View
    {
        return $service->getIndexPage();
    }

}
