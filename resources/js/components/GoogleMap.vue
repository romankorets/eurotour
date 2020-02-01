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
        <div class="b-popup" v-if="isShowPlacePopUp">
            <div class="row justify-content-center b-popup-content">
                <div class="col-md-10">
                    <div class="row justify-content-end">
                        <span title="Закрити вікно" class="close" v-on:click="closePlacePopUp"/>
                    </div>
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
                    <div class="row justify-content-around like">
                        <i class="fa fa-thumbs-up" v-on:click="toggleLike('like')" :class="{'fa-pressed': isLike}"/>
                        <i class="fa fa-thumbs-down" v-on:click="toggleLike('dislike')" :class="{'fa-pressed': isDislike}"/>
                    </div>
                    <div class="row justify-content-around count">
                        <span>{{this.numberOfLikes}}</span>
                        <span>{{this.numberOfDislikes}}</span>
                    </div>
                    <div class="row justify-content-center">
                        <div class="rating-mini">
                            <span v-for="(rating,index) in placeToShowInPopUp.rating" class="active"/>
                            <span v-if="placeToShowInPopUp.rating < 10"
                                  v-for="(rating,index) in 10 - placeToShowInPopUp.rating"/>
                        </div>
                    </div>
                    <div id="comment-input" class="row justify-content-end">
                        <textarea type="text" v-model="comment" placeholder="Введіть комментар"/>
                    </div>
                    <div class="row justify-content-end">
                        <button id="submitComment" v-on:click="sendComment" class="btn btn-primary">Відправити</button>
                    </div>
                    <div class="row justify-content-center">
                        <h3>Комментарі</h3>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div v-for="comment in placeToShowInPopUp.comments"
                                 class="row justify-content-start comment">
                                <div class="col-md-12">
                                    <div v-if="checkIfCurrentUserAdmin()" class="row justify-content-end">
                                        <span title="Вимкнути комментар" class="close" v-on:click="disableComment(comment.id)"/>
                                    </div>
                                    <div class="row name">{{comment.user['name']}}</div>
                                    <div class="row date">{{comment.created_at}}</div>
                                    <div class="row body">
                                        <p>{{comment.body}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- TODO: add rendering of routes on map  -->
<!-- TODO: add changing url for tour -->
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
                isShowPlacePopUp: false,
                windowHref: '/home',
                comment: '',
                currentUserRoles: null,
                isLike: false,
                isDislike: false,
            }
        },
        created() {
            this.fetchTours();
        },
        mounted() {
            this.init();
            this.fetchPlaces();
            this.setWindowUrl();
            this.setCurrentUserRole();
        },
        computed: {
            placeToShowInPopUp: function () {
                let start = this.windowHref.indexOf('#') + 1;
                let end = this.windowHref.length;

                let slug = this.windowHref.substring(start, end);
                for (let i = 0; i < this.places.length; i++) {
                    if (this.places[i]['slug'] == slug) {
                        for (let j = 0; j < this.places[i]['likes'].length; j++){
                            if(this.places[i]['likes'][j]['user']['id'] == this.userId){
                                if(this.places[i]['likes'][j]['value'] == 1){
                                    this.isLike = true;
                                    this.isDislike = false;
                                } else {
                                    this.isLike = false;
                                    this.isDislike = true;
                                }
                            }
                        }
                        return this.places[i];
                    }
                }
            },
            numberOfLikes: function () {
                let counter = 0;
                for(let i = 0; i < this.placeToShowInPopUp['likes'].length; i++){
                    if(this.placeToShowInPopUp['likes'][i]['value'] == 1){
                        counter++;
                    }
                }
                return counter;
            },
            numberOfDislikes: function () {
                let counter = 0;
                for(let i = 0; i < this.placeToShowInPopUp['likes'].length; i++){
                    if(this.placeToShowInPopUp['likes'][i]['value'] == 0){
                        counter++;
                    }
                }
                return counter;
            }
        },
        watch: {
            windowHref: function (newWindowHref) {
                this.isShowPlacePopUp = this.checkIfHasPlaceSlug(newWindowHref);
            },
        },
        methods: {
            init() {
                this.map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 4,
                    center: new google.maps.LatLng(49.698753, 19.404233),
                    mapTypeId: 'roadmap'
                })
            },

            async sendLike(value){
                if(value === 'like'){
                    value = 1;
                } else value = 0;
                await axios.post('api/like', {
                    'user_id': this.userId,
                    'place_id': this.placeToShowInPopUp.id,
                    'value': value
                })
            },

            async deleteLike(){
                await axios.delete('api/like/'+ this.userId + '/'+ this.placeToShowInPopUp.id +'/delete');
            },

            async updateLike(value){
                if(value === 'like'){
                    value = 1;
                } else value = 0;
                await axios.put('api/like/'+ this.userId + '/'+ this.placeToShowInPopUp.id +'/update', {
                    'value': value
                })
            },

            toggleLike(value){
                if(!this.isLike && !this.isDislike){  // create like if it doesn't exist
                    this.sendLike(value);
                    if (value === 'like'){
                        this.isLike = true;
                        this.isDislike = false;
                    } else {
                        this.isLike = false;
                        this.isDislike = true;
                    }
                } else {
                    if(value === 'like'){
                        if(this.isLike){
                            this.deleteLike();
                            this.isLike = false;
                        } else{
                            this.updateLike('like');
                            this.isLike = true;
                            this.isDislike = false;
                        }
                    } else {
                        if(this.isDislike){
                            this.deleteLike();
                            this.isDislike = false;
                        } else{
                            this.updateLike('dislike');
                            this.isLike = false;
                            this.isDislike = true;
                        }
                    }
                }
            },

            async setCurrentUserRole(){
                await axios.get('api/user/'+ this.userId +'/roles').then(response =>{
                    this.currentUserRoles = JSON.parse(response.data);
                    console.log('Ролі користувача');
                    console.log(this.currentUserRoles);
                })
            },

             async disableComment(id){
                 await axios.put('api/comment/'+ id, {
                    'enabled' : false
                }).then(response =>{
                    console.log(response)
                });
                 this.fetchPlaces();
            },

            checkIfCurrentUserAdmin(){
                for(let i = 0; i < this.currentUserRoles.length; i++){
                    if (this.currentUserRoles[i]['slug'] == 'admin'){
                        return true;
                    }
                }
                return false;
            },

            async sendComment() {
                await axios.post('api/comment', {
                    'user_id': this.userId,
                    'place_id': this.placeToShowInPopUp.id,
                    'body': this.comment
                });
                this.comment = '';
                this.fetchPlaces();
            },

            checkIfHasPlaceSlug(windowHref) {
                // for (let i = 0; i < windowHref.length; i++) {
                //     if (windowHref[i] === '#' && windowHref[i] !== windowHref[windowHref.length - 1]) {
                //         return true;
                //     }
                // }
                // return false;
                for (let i = 0; i < this.places.length; i++) {
                    if(windowHref.indexOf('#' + this.places[i]['slug']) + 1){
                        return true;
                    }
                }
                return false;
            },

            closePlacePopUp() {
                this.windowHref = "/home#";
                window.location.href = this.windowHref;
            },
            setWindowUrl() {
                this.windowHref = window.location.href;
            },
            async fetchPlaces() {
                await axios.get('api/place/index').then(response => {
                        console.log(response.data);
                        this.places = JSON.parse(response.data);
                        this.addMarkers();
                        for (let i = 0; i < this.places.length; i++) {
                            this.places[i].photos = JSON.parse(this.places[i].photos);
                        }
                        console.log('Місця: ');
                        console.log(this.places);
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
                    console.log(this.placeToShowInPopUp);
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
