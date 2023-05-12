<?php

namespace App\Services;

use App\Models\PeopleData;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeService
{

    private function getCountData(): array
    {
        $isServiceAvailable = !!PeopleData::orderByDesc('created_at')
            ->where('created_at', '>', Carbon::now()->subMinute(1))
            ->first();
        if ($isServiceAvailable) {
            $count = PeopleData::orderByDesc('created_at')
                ->where('created_at', '>', Carbon::now()->subMinute())
                ->first()
                ->count;
        }
        return [
            'isAvailable' => $isServiceAvailable,
            'count' => $count ?? null,
        ];
    }
    public function getIndexPage(): View
    {
        return view('dashboard', $this->getCountData());
    }

    public function storeToken(Request $request): RedirectResponse
    {
        $token = $request->user()->createToken($request->token_name);
        return redirect()->route('home.token.create')->with(['token' => $token->plainTextToken]);
    }

    public function getLastVideo(): JsonResponse
    {
        $files = Storage::disk('public')->files('videos');
        usort($files, function($a, $b) {
            return Storage::disk('public')->lastModified($a) > Storage::disk('public')->lastModified($b);
        });
        $lastFile = end($files);
        $dateString = substr($lastFile, strlen("videos/stream_"), strlen("2023-05-12_20-32-11"));
        $dateString = str_replace("_", " ", $dateString);
        $date = Carbon::parse($dateString);
        return response()->json([
            'url' => 'https://uvuv643.ru/storage/' . $lastFile,
            'created_at' => $date->format('Y-m-d h:i:s')
        ]);
    }

    public function getCount(): View
    {
        return view('people-count', $this->getCountData());
    }

}