@if ($isAvailable)
    <div class="home__stats__count">
        На данный момент в аудитории:
        @if ($count < 5)
            <span class="text-green-700">{{ $count }}</span>
        @elseif ($count < 15)
            <span class="text-orange-600">{{ $count }}</span>
        @elseif($count < 30)
            <span class="text-orange-700">{{ $count }}</span>
        @else
            <span class="text-red-500">{{ $count }}</span>
        @endif

        @if ($count >= 10 && $count <= 20 || $count % 10 <= 1 || $count % 10 >= 5) человек @else человека @endif
    </div>
@else
    <div class="home__stats__count pb-4">
        На данный момент видеокамера выключена. Невозможно посчитать количество людей в аудитории
    </div>
@endif