<?php

namespace App\Services;

use App\Models\PeopleData;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class HomeService
{



    public function getIndexPage(): View
    {
        $isServiceAvailable = !!PeopleData::orderByDesc('created_at')
          ->where('created_at', '>', Carbon::now()->subMinutes(3))
          ->first();
        if ($isServiceAvailable) {
            $count = PeopleData::orderByDesc('created_at')
              ->where('created_at', '>', Carbon::now()->subMinutes(3))
              ->first()
              ->count;
        }
        $data = [
          'isAvailable' => $isServiceAvailable,
          'count' => $count ?? null,
        ];
        return view('dashboard', $data);
    }


}