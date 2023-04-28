<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Services\HomeService;
use Illuminate\Support\Collection;

class HomeController extends Controller
{

    public function index(HomeService $service): View
    {
        return $service->getIndexPage();
    }

    public function graphic(HomeService $service): Collection
    {
        $random_data = [];
        for ($i = 0; $i < 1000; $i++) {
            $random_data[] = [
                'time' => mt_rand(1, 1700),
                'count' => mt_rand(1, 50)
            ];
        }
        return collect($random_data);
    }

}
