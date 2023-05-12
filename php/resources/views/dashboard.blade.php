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

                            <div class="home__stats__graphic">
                                <div class="home__stats__intervals">
                                    <a href="#" data-time="7d">3d</a>
                                    <a href="#" data-time="1d">1d</a>
                                    <a href="#" data-time="12h">12h</a>
                                    <a href="#" data-time="4h">4h</a>
                                    <a href="#" data-time="2h">2h</a>
                                    <a href="#" data-time="1h">1h</a>
                                    <a href="#" data-time="30m">30m</a>
                                    <a href="#" data-time="10m">10m</a>
                                </div>
                                <div class="home__stats__history">
                                    <canvas id="graphic"></canvas>
                                </div>
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

            let currentCanvas
            let lastUsedTime = '1h'

            function updateGraphic(data) {
                if (currentCanvas) {
                    if (JSON.stringify(currentCanvas.data.labels === JSON.stringify(data.map(row => row.time)))) {
                        currentCanvas.data.datasets[0].data = data.map(row => row.count)
                    } else {
                        currentCanvas.data = {
                            labels: data.map(row => row.time),
                            datasets: [
                                {
                                    data: data.map(row => row.count)
                                }
                            ]
                        }
                    }
                    currentCanvas.update();
                } else {
                    currentCanvas = new Chart(
                        document.getElementById('graphic'),
                        {
                            type: 'bar',
                            data: {
                                labels: data.map(row => row.time),
                                datasets: [
                                    {
                                        data: data.map(row => row.count)
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
                }
            }

            function getGraphic(time) {
                lastUsedTime = time
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch('{{ route('home.graphic.data') }}?time=' + time,
                    {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Authorization': 'Bearer {{ auth()->user()->createToken('api-token')->plainTextToken }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(response => {
                        updateGraphic(response)
                    })

            }

            $(document).ready(function () {

                let intervalButtonsElement = $('.home__stats__intervals a')

                getGraphic(lastUsedTime)
                intervalButtonsElement.on('click', function (event) {
                    event.preventDefault()
                    getGraphic($(this).attr('data-time'))
                })

                setInterval(() => {
                    getGraphic(lastUsedTime)
                }, 10000)
            })

        </script>

    @endpush
</x-app-layout>
