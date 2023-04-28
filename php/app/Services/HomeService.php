<?php

namespace App\Services;

use App\Models\PeopleData;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class HomeService
{

    private function getGraphicData(string $timeInterval): Collection
    {
        if (preg_match('/^[a-z]/', $timeInterval, $matches)) {
            $timeType = $matches[0];
        }
        $amountOfTime = intval($timeInterval);
        if (!empty($timeType) && $amountOfTime > 0) {
            switch ($timeType) {
                case 'h': {
                    return PeopleData::where('created_at', '>', Carbon::now()->subHours($amountOfTime))->get();
                }
                case 'd': {
                    return PeopleData::where('created_at', '>', Carbon::now()->subDays($amountOfTime))->get();
                }
                default: {
                    return PeopleData::where('created_at', '>', Carbon::now()->subMinutes($amountOfTime))->get();
                }
            }
        }
        return collect();
    }

    public function getIndexPage(): View
    {
        $isServiceAvailable = !!PeopleData::orderByDesc('created_at')
          ->where('created_at', '>', Carbon::now()->subMinutes(1))
          ->first();
        if ($isServiceAvailable) {
            $count = PeopleData::orderByDesc('created_at')
              ->where('created_at', '>', Carbon::now()->subMinutes(1))
              ->first()
              ->count;
        }
        $data = [
          'available' => $isServiceAvailable,
          'count' => $count ?? null,
        ];
        return view('dashboard', $data);
    }

    public function getGraphic(Request $request): View
    {
        $graphicData = $this->getGraphicData('1d');
        return view('components.graphic', [
            'history' => $graphicData
        ]);
    }

}