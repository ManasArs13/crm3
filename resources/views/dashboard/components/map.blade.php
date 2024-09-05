<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
<div id="map"></div>
<script>
    let map = new google.maps.Map(document.getElementById('map'), {
        center: new google.maps.LatLng(55.614051,37.9580056),
        zoom: 6,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true,
        gestureHandling: 'greedy'
    })
    let pois = [
        {label: "Ribeirão Preto", "latLng": new google.maps.LatLng(-21.214051,-47.9580056)},
        {label: "Jardim São José/Ribeirão Preto", "latLng": new google.maps.LatLng(-21.2306455,-47.7722293)},
        {label: "Uberlândia", "latLng": new google.maps.LatLng(-18.9218962,-48.3336058)},
        {label: "São Paulo", "latLng": new google.maps.LatLng(-23.6815314,-46.8754974)},
    ]

    /**
     * Handy functions to project lat/lng to pixel
     * Extracted from: https://developers.google.com/maps/documentation/javascript/examples/map-coordinates
     **/
    function project(latLng) {
        let TILE_SIZE = 256

        let siny = Math.sin(latLng.lat() * Math.PI / 180)

        // Truncating to 0.9999 effectively limits latitude to 89.189. This is
        // about a third of a tile past the edge of the world tile.
        siny = Math.min(Math.max(siny, -0.9999), 0.9999)

        return new google.maps.Point(
            TILE_SIZE * (0.5 + latLng.lng() / 360),
            TILE_SIZE * (0.5 - Math.log((1 + siny) / (1 - siny)) / (4 * Math.PI)))
    }
    const locations =[] ;

     function showAddressOnMap() {

        fetch('/map_data').then(response => response.json())
            .then(data => {
                data.orderDates.map((order)=>{
                    if (order['delivery'] !== null  && order['delivery']['original_name'] !== null){
                        fetch(`https://api.opencagedata.com/geocode/v1/json?q=${encodeURIComponent(order['delivery']['original_name'])}&key=9a57dd9201d34f2a8a0062a2df451611`)
                                .then(response => response.json())
                                .then(data => {
                                    console.log(data)
                                    if (data['results'].length > 0) {
                                       locations.push({position:data['results'][0]['geometry'],weight:order['weight']});
                                    }
                                })
                                .catch(e=>{
                                    console.log(e)})
                                    }
                                  })
            })
    }
    async function initMap() {
        await showAddressOnMap();
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
        const map = new Map(document.getElementById("map"), {
            center: { lat: 49.22,  lng:44.49 },
            zoom: 4,
            mapId: "4504f8b37365c3d0",
        });

        setTimeout(()=>{
            locations.forEach((data)=>{
                console.log(data)
                const priceTag = document.createElement("div");
                priceTag.className = "price-tag";
                priceTag.textContent = `${data['weight']}т`;
                const marker = new AdvancedMarkerElement({
                    map,
                    position:data['position'],
                    content: priceTag,
                });
            })
        },1000)
    }
    initMap();

    /**
     * Handy functions to project lat/lng to pixel
     * Extracted from: https://developers.google.com/maps/documentation/javascript/examples/map-coordinates
     **/
    function getPixel(latLng, zoom) {
        let scale = 1 << zoom
        let worldCoordinate = project(latLng)
        return new google.maps.Point(
            Math.floor(worldCoordinate.x * scale),
            Math.floor(worldCoordinate.y * scale))
    }

    /**
     * Given a map, return the map dimension (width and height)
     * in pixels.
     **/
    function getMapDimenInPixels(map) {
        let zoom = map.getZoom()
        let bounds = map.getBounds()
        let southWestPixel = getPixel(bounds.getSouthWest(), zoom)
        let northEastPixel = getPixel(bounds.getNorthEast(), zoom)
        return {
            width: Math.abs(southWestPixel.x - northEastPixel.x),
            height: Math.abs(southWestPixel.y - northEastPixel.y)
        }
    }

    /**
     * Given a map and a destLatLng returns true if calling
     * map.panTo(destLatLng) will be smoothly animated or false
     * otherwise.
     *
     * optionalZoomLevel can be optionally be provided and if so
     * returns true if map.panTo(destLatLng) would be smoothly animated
     * at optionalZoomLevel.
     **/
    function willAnimatePanTo(map, destLatLng, optionalZoomLevel) {
        let dimen = getMapDimenInPixels(map)

        let mapCenter = map.getCenter()
        optionalZoomLevel = !!optionalZoomLevel ? optionalZoomLevel : map.getZoom()

        let destPixel = getPixel(destLatLng, optionalZoomLevel)
        let mapPixel = getPixel(mapCenter, optionalZoomLevel)
        let diffX = Math.abs(destPixel.x - mapPixel.x)
        let diffY = Math.abs(destPixel.y - mapPixel.y)

        return diffX < dimen.width && diffY < dimen.height
    }

    /**
     * Returns the optimal zoom value when animating
     * the zoom out.
     *
     * The maximum change will be currentZoom - 3.
     * Changing the zoom with a difference greater than
     * 3 levels will cause the map to "jump" and not
     * smoothly animate.
     *
     * Unfortunately the magical number "3" was empirically
     * determined as we could not find any official docs
     * about it.
     **/
    function getOptimalZoomOut(latLng, currentZoom) {
        if(willAnimatePanTo(map, latLng, currentZoom - 1)) {
            return currentZoom - 1
        } else if(willAnimatePanTo(map, latLng, currentZoom - 2)) {
            return currentZoom - 2
        } else {
            return currentZoom - 3
        }
    }

    /**
     * Given a map and a destLatLng, smoothly animates the map center to
     * destLatLng by zooming out until distance (in pixels) between map center
     * and destLatLng are less than map width and height, then panTo to destLatLng
     * and finally animate to restore the initial zoom.
     *
     * optionalAnimationEndCallback can be optionally be provided and if so
     * it will be called when the animation ends
     **/
    function smoothlyAnimatePanToWorkarround(map, destLatLng, optionalAnimationEndCallback) {
        let initialZoom = map.getZoom(), listener

        function zoomIn() {
            if(map.getZoom() < initialZoom) {
                map.setZoom(Math.min(map.getZoom() + 3, initialZoom))
            } else {
                google.maps.event.removeListener(listener)

                //here you should (re?)enable only the ui controls that make sense to your app
                map.setOptions({draggable: true, zoomControl: true, scrollwheel: true, disableDoubleClickZoom: false})

                if(!!optionalAnimationEndCallback) {
                    optionalAnimationEndCallback()
                }
            }
        }

        function zoomOut() {
            if(willAnimatePanTo(map, destLatLng)) {
                google.maps.event.removeListener(listener)
                listener = google.maps.event.addListener(map, 'idle', zoomIn)
                map.panTo(destLatLng)
            } else {
                map.setZoom(getOptimalZoomOut(destLatLng, map.getZoom()))
            }
        }

        map.setOptions({draggable: false, zoomControl: false, scrollwheel: false, disableDoubleClickZoom: true})
        map.setZoom(getOptimalZoomOut(destLatLng, initialZoom))
        listener = google.maps.event.addListener(map, 'idle', zoomOut)
    }

    function smoothlyAnimatePanTo(map, destLatLng) {
        if(willAnimatePanTo(map, destLatLng)) {
            map.panTo(destLatLng)
        } else {
            smoothlyAnimatePanToWorkarround(map, destLatLng)
        }
    }

    function onItemClick(index) {
        smoothlyAnimatePanTo(map, pois[index].latLng)
    }

</script>
<style>
    #map {
        width: 100%;
        height: 40vh;
    }
    .price-tag{
        width:80px;
        height:80px;
        background-color: rgb(217, 217, 217);
        color: white;
        font-size: 20px;
        border-radius: 80px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
