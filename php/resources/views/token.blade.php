<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Создание токена') }}
        </h2>
    </x-slot>

    <div class="home py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('home.token.store') }}" method="post">

                        @csrf
                        <div class="mt-4 mb-4">
                            <x-input-label for="token_name" :value="__('Название токена')" />
                            <x-text-input id="token_name" class="block mt-1 w-full" placeholder="Введите название токена" type="text" name="token_name" :value="old('token_name')" required autofocus />
                        </div>

                        <x-primary-button>
                            {{ __('Генерировать токен') }}
                        </x-primary-button>

                    </form>
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

            function updateGraphic(data) {
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

            function getGraphic(time) {

                fetch('{{ route('home.graphic.data') }}?time=' + time)
                    .then(response => response.json())
                    .then(response => {
                        updateGraphic(response)
                    })

            }

            $(document).ready(function () {

                let intervalButtonsElement = $('.home__stats__intervals a')

                getGraphic('1h')
                intervalButtonsElement.on('click', function (event) {
                    event.preventDefault()
                    currentCanvas.destroy()
                    getGraphic($(this).attr('data-time'))
                })

            })

        </script>

    @endpush
</x-app-layout>
