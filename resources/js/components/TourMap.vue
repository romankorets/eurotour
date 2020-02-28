<template>
    <div class="row justify-content-center">
        <div id="tour-map"></div>
    </div>
</template>

<script>
    export default {
        props: ['tour'],
        data() {
            return{
                tourMap: null,
                tourToShow: this.tour,

                directionRequest: {
                    origin: null,
                    destination: null,
                    travelMode: 'DRIVING',
                    waypoints: [],
                    optimizeWaypoints: true,
                    avoidFerries: true,
                    avoidHighways: true,
                    avoidTolls: true,
                }
            }
        },
        created: async function () {
            console.log('тур карти');
            console.log(this.tour);
        },
        mounted() {
            console.log('Tour-map mounted.');
            this.initTourMap();
            this.initDirection();
            this.addMarkers();
        },

        methods: {
            initDirection(){
                var directionsService = new google.maps.DirectionsService();
                var directionsRenderer = new google.maps.DirectionsRenderer({suppressMarkers: true});
                directionsRenderer.setMap(this.tourMap);
                for (let i = 0; i < this.tourToShow['places'].length; i++){
                    if (this.tourToShow['places'][i]['pivot']['isStartPlace'] === 1){
                        this.directionRequest.origin = {
                            lat: Number(this.tourToShow['places'][i]['lat']),
                            lng: Number(this.tourToShow['places'][i]['lng'])
                        };
                    }
                    if (this.tourToShow['places'][i]['pivot']['isFinishPlace'] === 1){
                        this.directionRequest.destination = {
                            lat: Number(this.tourToShow['places'][i]['lat']),
                            lng: Number(this.tourToShow['places'][i]['lng'])
                        };
                    }
                    if(this.tourToShow['places'][i]['pivot']['isFinishPlace'] === 0 &&
                        this.tourToShow['places'][i]['pivot']['isStartPlace'] === 0){
                        let coords = {
                            lat: Number(this.tourToShow['places'][i]['lat']),
                            lng: Number(this.tourToShow['places'][i]['lng'])
                        };
                        this.directionRequest.waypoints.push({
                            location: coords,
                            stopover: true
                        });
                    }
                }
                directionsService.route(this.directionRequest, function(result, status) {
                    if (status == 'OK') {
                        directionsRenderer.setDirections(result);
                    }
                });

            },
            initTourMap() {
                this.tourMap = new google.maps.Map(document.getElementById('tour-map'), {
                    zoom: 4,
                    center: new google.maps.LatLng(49.698753, 19.404233),
                    mapTypeId: 'roadmap'
                });
                console.log('Tour map ініційовано');
            },

            addMarkers() {
                for (let i = 0; i < this.tourToShow['places'].length; i++) {
                    let coords = {
                        lat: Number(this.tourToShow['places'][i]['lat']),
                        lng: Number(this.tourToShow['places'][i]['lng'])
                    };
                    let marker;
                    if (this.tourToShow['places'][i]['pivot']['isStartPlace'] === 1){
                        marker = new google.maps.Marker({position: coords, map: this.tourMap, label: 'S'});
                    }
                    else if (this.tourToShow['places'][i]['pivot']['isFinishPlace'] === 1){
                        marker = new google.maps.Marker({position: coords, map: this.tourMap, label: 'F'});
                    } else {
                        marker = new google.maps.Marker({position: coords, map: this.tourMap});
                    }
                    var context = this;
                    marker.addListener('click', function () {
                        context.$router.push({ query: Object.assign({}, context.$route.query, { place: context.tourToShow['places'][i]['slug'] }) });
                    });
                }
            },
        }
    }
</script>
