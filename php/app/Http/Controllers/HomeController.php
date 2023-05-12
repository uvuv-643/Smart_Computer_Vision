<?php

namespace App\Http\Controllers;

use App\Models\PeopleData;
use App\Models\User;
use App\Services\HomeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{

    public function index(HomeService $service): View
    {
        return $service->getIndexPage();
    }

    public function create(): View
    {
        return view('token');
    }

    public function store(Request $request, HomeService $service): RedirectResponse
    {
        return $service->storeToken($request);
    }

    public function getLastVideo(HomeService $service): JsonResponse
    {
        return $service->getLastVideo();
    }

    public function test()
    {
        User::create([
            'name' => 'Artem',
            'email' => 'artem_zinatulin643@mail.ru',
            'password' => Hash::make('password'),
        ]);
        PeopleData::query()->delete();
    }

}
