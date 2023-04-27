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
                            <div class="home__video__title">Онлайн-трансляция</div>
                            <video controls></video>
                        </div>
                        <div class="home__stats">
                            <div class="home__stats__title">Статистика в режиме реального времени</div>
                            <div class="home__stats__count">
                                На данный момент в аудитории: {{ mt_rand(1, 15) }} человек
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
    @endpush
</x-app-layout>
