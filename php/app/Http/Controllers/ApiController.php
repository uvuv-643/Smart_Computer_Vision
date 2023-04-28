<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use App\Services\HomeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function graphic(Request $request, ApiService $service): JsonResponse
    {
        return $service->getGraphic($request);
    }

    public function store(Request $request, ApiService $service): JsonResponse
    {
        return $service->store($request);
    }

}
