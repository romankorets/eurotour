<template>
    <div>
        <div id="map"></div>
    </div>
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
                let i = 1;
                let condition = true;
                while(condition) {
                    await axios.get('api/places?page='+ i).then(response => {
                            for (let k = 0; k < response.data.data.length; k++){
                                console.log(response);
                                this.places.push(response.data.data[k]);
                            }
                            if (response.data.last_page === i){
                                condition = false;
                            }
                            this.addMarkers();
                        }
                    ).catch(error => console.log(error));
                    i++;
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
