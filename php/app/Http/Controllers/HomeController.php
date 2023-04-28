<?php

namespace App\Http\Controllers;

use App\Models\PeopleData;
use App\Services\HomeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
        PeopleData::query()->delete();
        for ($i = 0; $i < 500; $i++) {
            $rand = 50 + mt_rand(10, 50) * sin($i / 10 + mt_rand(0, 10) / 20);
            PeopleData::insert([
              'count' => $rand < 20 ? 0 : $rand,
              'created_at' => Carbon::now()->subMinutes($i),
              'updated_at' => Carbon::now()->subMinutes($i),
            ]);
        }

    }

}
