<template>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="row justify-content-center">
                <h1>Тури</h1>
            </div>
            <div class="row justify-content-center tour" v-for="tour in tours">
                <div class="col-md-12">
                    <div class="row justify-content-center">
                        <h3>{{ tour.name }}</h3>
                    </div>
                    <div class="row justify-content-center ">
                        <div class="col-md-4 bordered">
                            <div :id="'carouselControls' + tour.id" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item" :class="{active : index===0}" v-for="(photo, index) in tour.photos">
                                        <img class="d-block w-100" :src="'./storage/' + photo" :alt="tour.name">
                                    </div>
                                    <a class="carousel-control-prev" :href="'#carouselControls' + tour.id" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" :href="'#carouselControls' + tour.id" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 justify-content-center bordered">
                            <h4>Опис</h4>
                            {{ tour.description }}
                        </div>
                        <div class="col-md-3 justify-content-center bordered">
                            <h4>Тривалість (днів)</h4>
                            {{ tour.duration }}
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <a class="btn btn-primary" @click="showTour">Показати на карті</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div id="map"></div>
        </div>
    </div>

</template>
<!--  TODO: add PopUp window to show place info  -->
<!--  TODO: add rendering of routes on map  -->
<script>
    export default {
        data :function(){
            return {
                map : null,
                places: [],
                markers: [],
                tours: []
            }
        },
        mounted () {
            this.init();
            this.fetchPlaces();
            this.fetchTours();
        },
        methods: {
            init () {
                this.map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 4,
                    center: new google.maps.LatLng(49.698753, 19.404233),
                    mapTypeId: 'roadmap'
                })
            },

            showTour(){

            },

             fetchPlaces(){
                axios.get('api/place/index').then(response => {
                        console.log(response.data);
                        this.places = JSON.parse(response.data);
                        console.log(this.places);
                        console.log(this.places.length);
                        console.log(this.places[0]['lat']);
                        this.addMarkers();
                        for (let i = 0; i < this.places.length; i++){
                            this.places[i].photos = JSON.parse(this.places[i].photos);
                        }
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

            fetchTours(){
                axios.get('api/tour/index').then(response => {
                        this.tours = JSON.parse(response.data);
                        console.log('Тури : ');
                        console.log(this.tours);
                        for (let i = 0; i < this.tours.length; i++){
                            this.tours[i].photos = JSON.parse(this.tours[i].photos);
                        }
                        console.log('Тури : ');
                        console.log(this.tours);
                    }
                ).catch(error => console.log(error));
            },
        }
    }
</script>
