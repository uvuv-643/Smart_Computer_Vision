<?php

namespace App\Services;

use App\Models\PeopleData;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
        return response()->json([
            'url' => 'https://uvuv643.ru/storage/videos/stream_2023-05-12_17-44-35.webm',
            'created_at' => Carbon::now()->subMinute()
        ]);
    }

    public function getCount(): View
    {
        return view('people-count', $this->getCountData());
    }

}