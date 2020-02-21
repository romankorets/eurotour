<template>
    <div class="row justify-content-center b-container">
        <div class="col-md-12">
            <div class="row justify-content-center">
                <div id="map"></div>
            </div>
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
                    <div v-if="!isShowPlacePopUp && !isShowTourPopUp" class="row justify-content-center">
                        <button @click="showTour(tour.id)" class="btn btn-primary">Показати на карті</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="place-popup" class="b-popup" v-if="isShowPlacePopUp">
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
                        <i class="fa fa-thumbs-down" v-on:click="toggleLike('dislike')"
                           :class="{'fa-pressed': isDislike}"/>
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
                                        <span title="Вимкнути комментар" class="close"
                                              v-on:click="disableComment(comment.id)"/>
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
        <div id="tour-popup" class="b-popup" v-if="isShowTourPopUp">
            <div class="row justify-content-center b-popup-content">
                <div class="col-md-10">
                    <div class="row justify-content-end">
                        <span title="Закрити вікно" class="close" v-on:click="closeTourPopUp"/>
                    </div>
                    <div class="row justify-content-center">
                        <h1>Карта</h1>
                    </div>
                    <div class="row justify-content-center">
                        <tour-map :tour="tourToShowInPopUp"/>
                    </div>
                    <div class="row justify-content-center">
                        <h1>{{ tourToShowInPopUp.name }}</h1>
                    </div>
                    <div class="row justify-content-around">
                        <div class="col-md-4">
                            <div class="row justify-content-center">
                                <div :id="'carouselControls' + tourToShowInPopUp.id" class="carousel slide"
                                     data-ride="false">
                                    <div class="carousel-inner">
                                        <div class="carousel-item" :class="{active : index===0}"
                                             v-for="(photo, index) in tourToShowInPopUp.photos">
                                            <img class="d-block w-100" :src="'./storage/' + photo"
                                                 :alt="tourToShowInPopUp.name">
                                        </div>
                                        <a class="carousel-control-prev"
                                           :href="'#carouselControls' + tourToShowInPopUp.id"
                                           role="button"
                                           data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"/>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next"
                                           :href="'#carouselControls' + tourToShowInPopUp.id"
                                           role="button"
                                           data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"/>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="row justify-content-center">
                                <article>{{ tourToShowInPopUp.description }}</article>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- TODO: add showing of place when in tour -->
<script>
    export default {
        props: [
            'userId'
        ],
        data: function () {
            return {
                map: null,
                places: [],
                tours: [],
                isShowPlacePopUp: false,
                isShowTourPopUp: false,
                windowHref: '/home',
                comment: '',
                currentUserRoles: null,
                isLike: false,
                isDislike: false,
            }
        },
        created: async function () {
            await this.fetchTours();
        },
        mounted: async function () {
            await this.setCurrentUserRole();
            this.init();
            await this.fetchPlaces();
            this.setWindowUrl();
            var context = this;

            var _wr = function(type) {
                var orig = history[type];
                return function() {
                    var rv = orig.apply(this, arguments);
                    var e = new Event(type);
                    e.arguments = arguments;
                    window.dispatchEvent(e);
                    return rv;
                };
            };
            history.pushState = _wr('pushState');

            window.addEventListener('pushState', function(e) {
                context.windowHref = window.location.href;
                console.log('Адреса змінилась на ');
                console.log(context.windowHref);
            });

            window.onpopstate = function () {
                context.windowHref = window.location.href;
                console.log('Адреса змінилась на ');
                console.log(context.windowHref);
            }
        },
        computed: {
            tourToShowInPopUp: function () {
                for (let i = 0; i < this.tours.length; i++) {
                    if (this.windowHref.includes(this.tours[i]['slug'])) {
                        console.log('Тур для виводу');
                        console.log(this.tours[i]);
                        return this.tours[i];
                    }
                }
            },

            placeToShowInPopUp: function () {
                for (let i = 0; i < this.places.length; i++) {
                    if (this.windowHref.includes(this.places[i]['slug'])) {
                        for (let j = 0; j < this.places[i]['likes'].length; j++) {
                            if (this.places[i]['likes'][j]['user']['id'] == this.userId) {
                                if (this.places[i]['likes'][j]['value'] == 1) {
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
                for (let i = 0; i < this.placeToShowInPopUp['likes'].length; i++) {
                    if (this.placeToShowInPopUp['likes'][i]['value'] == 1) {
                        counter++;
                    }
                }
                return counter;
            },
            numberOfDislikes: function () {
                let counter = 0;
                for (let i = 0; i < this.placeToShowInPopUp['likes'].length; i++) {
                    if (this.placeToShowInPopUp['likes'][i]['value'] == 0) {
                        counter++;
                    }
                }
                return counter;
            }
        },
        watch: {
            windowHref: function (newWindowHref) {
                if (this.checkIfHasTourSlug(newWindowHref) && this.checkIfHasPlaceSlug(newWindowHref)) {
                    this.isShowTourPopUp = false;
                    this.isShowPlacePopUp = true;
                } else if (this.checkIfHasPlaceSlug(newWindowHref)) {
                    this.isShowPlacePopUp = true;
                    this.isShowTourPopUp = false;
                } else if (this.checkIfHasTourSlug(newWindowHref)) {
                    this.isShowPlacePopUp = false;
                    this.isShowTourPopUp = true;
                } else {
                    this.isShowPlacePopUp = false;
                    this.isShowTourPopUp = false;
                }
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
            showTour: function (id) {
                this.isShowTourPopUp = true;
                for (let i = 0; this.tours.length; i++) {
                    if (this.tours[i].id == id) {
                        this.isShowTourPopUp = true;
                        if (this.windowHref[this.windowHref.length - 1] === '#') {
                            this.windowHref = this.windowHref + this.tours[i]['slug'];
                        } else {
                            this.windowHref = this.windowHref + '#' + this.tours[i]['slug'];
                        }
                        history.pushState(null, null, window.location.href + '?tour=' + this.tours[i]['slug']);
                    }
                }
            },

            async sendLike(value) {
                if (value === 'like') {
                    value = 1;
                } else value = 0;
                let uri = window.location.search.substring(1);
                let params = new URLSearchParams(uri);
                await axios.post('api/place/'+ params.get('place') + '/like', {
                    'value': value
                })
            },

            async deleteLike() {
                let uri = window.location.search.substring(1);
                let params = new URLSearchParams(uri);
                await axios.delete('api/place/'+ params.get('place') +'/like/delete');
            },

            async updateLike(value) {
                if (value === 'like') {
                    value = 1;
                } else value = 0;
                let uri = window.location.search.substring(1);
                let params = new URLSearchParams(uri);
                await axios.put('api/place/'+ params.get('place') +'/like/update', {
                    'value': value
                })
            },

            toggleLike(value) {
                if (!this.isLike && !this.isDislike) {  // create like if it doesn't exist
                    this.sendLike(value);
                    if (value === 'like') {
                        this.isLike = true;
                        this.isDislike = false;
                    } else {
                        this.isLike = false;
                        this.isDislike = true;
                    }
                } else {
                    if (value === 'like') {
                        if (this.isLike) {
                            this.deleteLike();
                            this.isLike = false;
                        } else {
                            this.updateLike('like');
                            this.isLike = true;
                            this.isDislike = false;
                        }
                    } else {
                        if (this.isDislike) {
                            this.deleteLike();
                            this.isDislike = false;
                        } else {
                            this.updateLike('dislike');
                            this.isLike = false;
                            this.isDislike = true;
                        }
                    }
                }
            },

            async setCurrentUserRole() {
                await axios.get('api/user/roles').then(response => {
                    this.currentUserRoles = JSON.parse(response.data);
                    console.log('Ролі користувача');
                    console.log(this.currentUserRoles);
                })
            },

            async disableComment(id) {
                await axios.put('api/comment/' + id, {
                    'enabled': false
                }).then(response => {
                    console.log(response)
                });
                this.fetchPlaces();
            },

            checkIfCurrentUserAdmin() {
                for (let i = 0; i < this.currentUserRoles.length; i++) {
                    if (this.currentUserRoles[i]['slug'] == 'admin') {
                        return true;
                    }
                }
                return false;
            },

            async sendComment() {
                await axios.post('api/comment', {
                    'body': this.comment
                });
                this.comment = '';
                this.fetchPlaces();
            },

            checkIfHasTourSlug(windowHref) {
                for (let i = 0; i < this.tours.length; i++) {
                    if (windowHref.includes(this.tours[i]['slug'])) {
                        return true;
                    }
                }
                return false;
            },

            checkIfHasPlaceSlug(windowHref) {
                for (let i = 0; i < this.places.length; i++) {
                    if (windowHref.includes(this.places[i]['slug'])) {
                        return true;
                    }
                }
                return false;
            },

            closeTourPopUp() {
                this.windowHref = this.windowHref.replace('#' + this.tourToShowInPopUp['slug'], '');
                window.history.back();
            },

            closePlacePopUp() {
                this.windowHref = this.windowHref.replace('#' + this.placeToShowInPopUp['slug'], '');
                window.history.back();
            },
            setWindowUrl() {
                this.windowHref = window.location.href;
            },
            async fetchPlaces() {
                await axios.get('api/place/index').then(response => {
                        this.places = JSON.parse(response.data);
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
                    var context = this;
                    marker.addListener('click', function () {
                        context.isShowPlacePopUp = true;
                        if (context.windowHref[context.windowHref.length - 1] === '#') {
                            context.windowHref = context.windowHref + context.places[i]['slug'];
                        } else {
                            context.windowHref = context.windowHref + '#' + context.places[i]['slug'];
                        }
                        history.pushState(null, null, window.location.href + '?place=' + context.places[i]['slug']);
                        console.log('Адреса вікна');
                        console.log(window.location.href);
                    });
                }
            },

            async fetchTours() {
                await axios.get('api/tour/index').then(response => {
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
