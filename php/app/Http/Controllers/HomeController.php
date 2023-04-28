<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Services\HomeService;
use Ramsey\Collection\Collection;

class HomeController extends Controller
{

    public function index(HomeService $service): View
    {
        return $service->getIndexPage();
    }

    public function graphic(HomeService $service): Collection
    {
        return collect([
          ['time' => 123, 'count' => 15],
          ['time' => 124, 'count' => 14],
          ['time' => 125, 'count' => 16],
          ['time' => 126, 'count' => 11],
        ]);
    }

}
