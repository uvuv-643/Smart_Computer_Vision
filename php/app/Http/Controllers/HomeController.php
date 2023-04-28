<?php

namespace App\Http\Controllers;

use App\Models\PeopleData;
use App\Services\HomeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function test()
    {
        PeopleData::create([
          'count' => 15
        ]);
    }

}
