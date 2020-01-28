<template>
    <div id="map"></div>
</template>

<script>
    export default {
        data :function(){
            return {
                map : null,
                places: [],
                markers: []
            }
        },
        mounted () {
            this.init();
            this.fetchPlaces();
        },
        methods: {
            init () {
                this.map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 4,
                    center: new google.maps.LatLng(49.698753, 19.404233),
                    mapTypeId: 'roadmap'
                })
            },

             fetchPlaces(){
                axios.get('api/place/index').then(response => {
                        console.log(response.data);
                        this.places = JSON.parse(response.data);
                        console.log(this.places);
                        console.log(this.places.length);
                        console.log(this.places[0]['lat']);
                        this.addMarkers();
                    console.log(this.markers)
                    }
                ).catch(error => console.log(error));

            },

            addMarkers(){
                console.log('adding markers');
                for (let i = 0; i < this.places.length; i++){
                    let coords = {
                        lat: Number(this.places[i]['lat']),
                        lng: Number(this.places[i]['lng'])
                    };
                    let marker = new google.maps.Marker({position: coords, map: this.map});
                    this.markers.push({
                        id: this.places[i].id,
                        marker: marker
                    });
                }
            },
        }
    }
</script>
