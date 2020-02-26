<template>
    <div id="map"></div>
</template>

<script>
    export default {
        data: function () {
            return {
                map: null,
                places: [],
            }
        },
        mounted: function () {
            this.init();
            this.fetchPlaces();
            console.log('Component mounted.')
        },
        methods:{
            init() {
                this.map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 4,
                    center: new google.maps.LatLng(49.698753, 19.404233),
                    mapTypeId: 'roadmap'
                });
            },
            async fetchPlaces() {
                var countOfPlaces = 0;
                await axios.get('api/place/count').then(response => {
                    countOfPlaces = response.data;
                });
                let per_page = 2;
                let numberOfPages = Math.ceil(countOfPlaces / per_page);
                for (let i = 1; i <= numberOfPages; i++) {
                      await axios.get('api/place/index?page='+ i).then(response => {
                            for (let k = 0; k < response.data.data.length; k++){
                                this.places.push(response.data.data[k]);
                            }
                            this.addMarkers();
                        }
                    ).catch(error => console.log(error));
                }
                console.log('місця');
                console.log(this.places);
            },

            addMarkers() {
                for (let i = 0; i < this.places.length; i++) {
                    let coords = {
                        lat: Number(this.places[i]['lat']),
                        lng: Number(this.places[i]['lng'])
                    };
                    let marker = new google.maps.Marker({position: coords, map: this.map});
                    var context = this;
                    marker.addListener('click', function () {
                        context.$router.push({name: 'home', query: { place : context.places[i]['slug'] }});
                    });
                }
            },
        }
    }
</script>
