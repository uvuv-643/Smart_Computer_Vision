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

    public function getIndexPage(): View
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
        $data = [
            'isAvailable' => $isServiceAvailable,
            'count' => $count ?? null,
        ];
        return view('dashboard', $data);
    }

    public function storeToken(Request $request): RedirectResponse
    {
        $token = $request->user()->createToken($request->token_name);
        return redirect()->route('home.token.create')->with(['token' => $token->plainTextToken]);
    }

    public function getLastVideo(): JsonResponse
    {
        return response()->json([
            'url' => 'https://uvuv643.ru/assets/videos/frag_bunny.mp4',
            'created_at' => Carbon::now()->subMinute()
        ]);
    }

}