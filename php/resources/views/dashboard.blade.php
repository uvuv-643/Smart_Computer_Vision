<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Главная') }}
        </h2>
    </x-slot>

    <div class="home py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="home__title">Аудитория 358</div>
                    <div class="home__wrapper">
                        <div class="home__video">
{{--                        <div class="home__video__title">Онлайн-трансляция</div>--}}
                            <video controls></video>
                        </div>
                        <div class="home__stats">
                            <div class="home__stats__title">Статистика в режиме реального времени</div>

                            @php
                                $count = mt_rand(1, 50)
                            @endphp

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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/js/utils.js') }}"></script>
        <script src="{{ asset('assets/js/segments.js') }}"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            (async function() {

                fetch('{{ route('home.graphic.data') }}')
                    .then(response => response.json())
                    .then(response => {
                        new Chart(
                            document.getElementById('graphic'),
                            {
                                type: 'bar',
                                data: {
                                    labels: response.map(row => row.time),
                                    datasets: [
                                        {
                                            data: response.map(row => row.count)
                                        }
                                    ]
                                },
                                options: {
                                    plugins: {
                                        legend: {
                                            display: false
                                        },
                                        tooltips: {
                                            enabled: false
                                        }
                                    }
                                }
                            }
                        );
                    })

            })();
        </script>

    @endpush
</x-app-layout>
