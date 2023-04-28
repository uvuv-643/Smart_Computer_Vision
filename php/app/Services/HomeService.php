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

    private function getGraphicData(string $timeInterval): Collection
    {
        if (preg_match('/^[a-z]/', $timeInterval, $matches)) {
            $timeType = $matches[0];
        }
        $amountOfTime = intval($timeInterval);
        dd([$timeType, $amountOfTime, $timeInterval]);
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

    public function getGraphic(Request $request): JsonResponse
    {
        $graphicData = $this->getGraphicData($request->time);
        $segmentsCount = ceil($graphicData->count() / 200);
        $averages = $graphicData->chunk($segmentsCount)->map(function($chunk) {
            $average = $chunk->avg('count');
            return ['time' => $chunk->pluck('created_at')->last(), 'count' => $average];
        });
        return response()->json($averages);
    }

}