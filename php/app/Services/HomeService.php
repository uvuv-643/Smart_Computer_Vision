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
        if (preg_match('/[a-z]/', $timeInterval, $matches)) {
            $timeType = $matches[0];
        }
        $amountOfTime = intval($timeInterval);
        if (!empty($timeType) && $amountOfTime > 0) {
            switch ($timeType) {
                case 'h': {
                    return PeopleData::orderBy('created_at')->where('created_at', '>', Carbon::now()->subHours($amountOfTime))->get();
                }
                case 'd': {
                    return PeopleData::orderBy('created_at')->where('created_at', '>', Carbon::now()->subDays($amountOfTime))->get();
                }
                default: {
                    return PeopleData::orderBy('created_at')->where('created_at', '>', Carbon::now()->subMinutes($amountOfTime))->get();
                }
            }
        }
        return collect();
    }

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

    public function getGraphic(Request $request): JsonResponse
    {
        $graphicData = $this->getGraphicData($request->time);
        $segmentsCount = ceil($graphicData->count() / 100);
        $averages = $graphicData->chunk($segmentsCount)->map(function($chunk) {
            $average = $chunk->avg('count');
            return ['time' => Carbon::parse($chunk->pluck('created_at')->last())->format('d.m.Y H:i:s'), 'count' => $average];
        });
        return response()->json($averages);
    }

}