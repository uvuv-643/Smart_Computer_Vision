<?php

namespace App\Services;

use App\Models\PeopleData;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ApiService
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

    public function store(Request $request): JsonResponse
    {
        if ($request->count != null) {
            PeopleData::create([
              'count' => $request->count
            ]);
            return response()->json(['success' => true]);
        } else {
            return response()->json(['status' => false], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(['status' => false], 500);
    }


}