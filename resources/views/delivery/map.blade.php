
<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>{{ __('entity.' . $entity) }}</x-slot>
    @endif
    <x-slot:head>
        <script src="https://api-maps.yandex.ru/2.1/?apikey=83a5c651-59e9-4762-9fa9-d222e4aa50ab&lang=ru_RU" type="text/javascript"></script>
    </x-slot>
    <div class="w-11/12 mx-auto py-8 max-w-10xl">
        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ __('entity.' . $entity) }}</h3>
        @endif

        <div class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

            {{-- body --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto rounded">
                <div id="map" class="w-[100%] h-[700px]"></div>
            </div>

        </div>
    </div>
        <script>

            ymaps.ready(init);

            function init() {

                var myMap = new ymaps.Map("map", {
                    center: [44.556561, 33.526725],
                    zoom: 5
                });


                async function fetchMapData() {
                    try {
                        const response = await fetch('{{ route('api.get.coords') }}');
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        const data = await response.json();

                        var clusterer = new ymaps.Clusterer({
                            preset: 'islands#invertedVioletClusterIcons',
                            groupByCoordinates: false,
                            clusterDisableClickZoom: false,
                            clusterOpenBalloonOnClick: true,
                            clusterBalloonContentLayout: "cluster#balloonTwoColumns"
                        });

                        var placemarks = data.map(function(obj) {
                            return new ymaps.Placemark(obj.coords, {
                                balloonContent: obj.description
                            }, {
                                preset: 'islands#icon',
                                iconColor: '#0095b6'
                            });
                        });

                        clusterer.add(placemarks);
                        myMap.geoObjects.add(clusterer);
                    } catch (error) {
                        console.error('Error fetching map data:', error);
                    }
                }


                fetchMapData();
            }
        </script>
</x-app-layout>
