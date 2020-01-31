<template>
    <div class="row justify-content-center b-container">
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
                                    <div class="carousel-item" :class="{active : index===0}"
                                         v-for="(photo, index) in tour.photos">
                                        <img class="d-block w-100" :src="'./storage/' + photo" :alt="tour.name">
                                    </div>
                                    <a class="carousel-control-prev" :href="'#carouselControls' + tour.id" role="button"
                                       data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"/>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" :href="'#carouselControls' + tour.id" role="button"
                                       data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"/>
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
                        <a class="btn btn-primary">Показати на карті</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div id="map"></div>
        </div>
        <div class="b-popup" v-if="isShowPopUp">
            <div class="row justify-content-around b-popup-content">
                <div class="col-md-8 right-bordered">
                    <div class="row justify-content-center">
                        <h1>{{ placeToShowInPopUp.name }}</h1>
                    </div>
                    <div class="row justify-content-center">
                        <div :id="'carouselControls' + placeToShowInPopUp.id" class="carousel slide"
                             data-ride="false">
                            <div class="carousel-inner">
                                <div class="carousel-item" :class="{active : index===0}"
                                     v-for="(photo, index) in placeToShowInPopUp.photos">
                                    <img class="d-block w-100" :src="'./storage/' + photo"
                                         :alt="placeToShowInPopUp.name">
                                </div>
                                <a class="carousel-control-prev" :href="'#carouselControls' + placeToShowInPopUp.id"
                                   role="button"
                                   data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"/>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" :href="'#carouselControls' + placeToShowInPopUp.id"
                                   role="button"
                                   data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"/>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <h1>Опис</h1>
                        {{ placeToShowInPopUp.description }}
                    </div>
                    <div class="row justify-content-center">
                        <div class="rating-mini">
                            <span v-for="(rating,index) in placeToShowInPopUp.rating" class="active"/>
                            <span v-if="placeToShowInPopUp.rating < 10"
                                  v-for="(rating,index) in 10 - placeToShowInPopUp.rating"/>
                        </div>
                    </div>
                    <div id="comment-input" class="row justify-content-end">
                        <label for="comment">Залиште коментар</label>
                        <textarea id="comment" type="text" v-model="comment" placeholder="Введіть комментар"/>
                        <button id="submitComment" v-on:click="sendComment" class="btn btn-primary">Відправити</button>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row justify-content-end">
                        <span class="close" v-on:click="closePopUp"/>
                    </div>
                    <div v-for="comment in commentsToShow" class="row justify-content-center comment">
                        <span>{{}}</span>
                        <div>{{comment.body}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<!--  TODO: add rendering of routes on map  -->
<!-- TODO: add rendering name of user of comment -->
<!-- TODO: add like system -->
<script>
    export default {
        props: [
            'userId'
        ],
        data: function () {
            return {
                map: null,
                places: [],
                markers: [],
                tours: [],
                isShowPopUp: false,
                windowHref: '/home',
                comment: '',
                commentsToShow: null
            }
        },
        created() {
            this.fetchTours();
        },
        mounted() {
            this.init();
            this.fetchPlaces();
            this.setWindowUrl();
        },
        computed: {
            placeToShowInPopUp: function () {
                let start = this.windowHref.indexOf('#') + 1;
                let end = this.windowHref.length;
                let slug = this.windowHref.substring(start, end);
                for (let i = 0; i < this.places.length; i++) {
                    if (this.places[i]['slug'] == slug) {
                        this.getCommentsOfPlaceById(this.places[i].id);
                        return this.places[i];
                    }
                }
            },
        },
        watch: {
            windowHref: function (newWindowHref) {
                this.isShowPopUp = this.checkIfHasSlug(newWindowHref);
            }
        },
        methods: {
            init() {
                this.map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 4,
                    center: new google.maps.LatLng(49.698753, 19.404233),
                    mapTypeId: 'roadmap'
                })
            },


            getUserByCommentId(id){
                axios.get('/api/comment/' + id).then(response =>{
                    return JSON.parse(response.data);
                });
            },

            getCommentsOfPlaceById(id){
                axios.get('/api/place/' + id + '/comments').then(response =>{
                     this.commentsToShow = JSON.parse(response.data);
                });
            },

            sendComment(){
                axios.post('api/comment', {
                    'user_id': this.userId,
                    'place_id': this.placeToShowInPopUp.id,
                    'body': this.comment
                });
                this.comment = '';
            },

            checkIfHasSlug(windowHref){
                for (let i = 0; i < windowHref.length; i++) {
                    if (windowHref[i] === '#' && windowHref[i] !== windowHref[windowHref.length - 1]) {
                        return true;
                    }
                }
                return false;
            },
            closePopUp(){
                this.windowHref = "/home#";
                window.location.href = this.windowHref;
            },
            setWindowUrl() {
                this.windowHref = window.location.href;
            },
            fetchPlaces() {
                axios.get('api/place/index').then(response => {
                        console.log(response.data);
                        this.places = JSON.parse(response.data);
                        console.log(this.places);
                        console.log(this.places.length);
                        console.log(this.places[0]['lat']);
                        this.addMarkers();
                        for (let i = 0; i < this.places.length; i++) {
                            this.places[i].photos = JSON.parse(this.places[i].photos);
                        }
                    }
                ).catch(error => console.log(error));
            },

            addMarkers() {
                for (let i = 0; i < this.places.length; i++) {
                    let coords = {
                        lat: Number(this.places[i]['lat']),
                        lng: Number(this.places[i]['lng'])
                    };
                    let marker = new google.maps.Marker({position: coords, map: this.map});
                    this.markers.push({
                        id: this.places[i].id,
                        marker: marker
                    });
                    var context = this;
                    marker.addListener('click', function () {
                        context.isShowPopUp = true;
                        context.windowHref = "/home#" + context.places[i]['slug'];
                        window.location.href = context.windowHref;
                    });
                    console.log('Маркери');
                    console.log(this.markers);
                    console.log(this.placeSlugToShowInPopUp);
                }
            },

            fetchTours() {
                axios.get('api/tour/index').then(response => {
                        this.tours = JSON.parse(response.data);
                        console.log('Тури : ');
                        console.log(this.tours);
                        for (let i = 0; i < this.tours.length; i++) {
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
