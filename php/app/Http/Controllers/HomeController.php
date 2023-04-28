<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\HomeService;
use Illuminate\Support\Collection;

class HomeController extends Controller
{

    public function index(HomeService $service): View
    {
        return $service->getIndexPage();
    }

    public function graphic(Request $request, HomeService $service): JsonResponse
    {
        return $service->getGraphic($request);
    }

}
