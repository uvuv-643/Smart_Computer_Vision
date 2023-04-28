<?php

namespace App\Services;

use App\Models\PeopleData;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class HomeService
{

    public function getIndexPage(): View
    {
        $data = [
          'available' => !!PeopleData::orderByDesc('created_at')->where('created_at', '>', Carbon::now()->subMinutes(5))->first()
        ];
        dd($data);
        return view('dashboard', $data);
    }

}