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
            <div class="row col-md-12 justify-content-center">
                <pagination-tour :pagination="paginationTours" @paginate = 'fetchTours()'/>
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
                        <div :id="'carouselControls' + placeToShowInPopUp.slug" class="carousel slide"
                             data-ride="false">
                            <div class="carousel-inner">
                                <div class="carousel-item" :class="{active : index===0}"
                                     v-for="(photo, index) in placeToShowInPopUp.photos">
                                    <img class="d-block w-100" :src="'./storage/' + photo"
                                         :alt="placeToShowInPopUp.name">
                                </div>
                                <a class="carousel-control-prev" :href="'#carouselControls' + placeToShowInPopUp.slug"
                                   role="button"
                                   data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"/>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" :href="'#carouselControls' + placeToShowInPopUp.slug"
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

<script>
    import PaginationTour from "./PaginationTour";
    export default {
        components: {PaginationTour},
        data: function () {
            return {
                map: null,
                userId: null,
                places: [],
                tours: [],
                isShowPlacePopUp: false,
                isShowTourPopUp: false,
                comment: '',
                currentUserRoles: null,
                isLike: false,
                isDislike: false,
                paginationTours: {
                    current_page : 1,
                    first_page_url: null,
                    from: null,
                    last_page: null,
                    last_page_url: null,
                    next_page_url: null,
                    path: null,
                    per_page: null,
                    prev_page_url: null,
                    to: null,
                    total: null
                },
            }
        },
        created: async function () {
            await this.fetchTours();
        },
        mounted: async function () {
            await this.setUserId();
            await this.setCurrentUserRole();
            this.init();
            await this.fetchPlaces();
            this.showModal();
        },

        computed: {
            tourToShowInPopUp: function () {
                for (let i = 0; i < this.tours.length; i++) {
                    if (this.$route.query.tour === this.tours[i]['slug']) {
                        console.log('Тур для виводу');
                        console.log(this.tours[i]);
                        return this.tours[i];
                    }
                }
            },

            placeToShowInPopUp: function () {
                for (let i = 0; i < this.places.length; i++) {
                    if (this.$route.query.place === this.places[i]['slug']) {
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

            isShowPlacePopUp(value){
                if (value){
                    this.setUserLike();
                }
            },

            $route (to, from){
                this.showModal();
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
                        this.$router.push({ path: 'home', query:{ tour: this.tours[i]['slug']}});
                    }
                }
            },

            showModal(){
                if (this.checkIfHasTourSlug() && this.checkIfHasPlaceSlug()) {
                    this.isShowTourPopUp = false;
                    this.isShowPlacePopUp = true;
                } else if (this.checkIfHasPlaceSlug()) {
                    this.isShowPlacePopUp = true;
                    this.isShowTourPopUp = false;
                } else if (this.checkIfHasTourSlug()) {
                    this.isShowPlacePopUp = false;
                    this.isShowTourPopUp = true;
                } else {
                    this.isShowPlacePopUp = false;
                    this.isShowTourPopUp = false;
                }
            },

            setUserId(){
                axios.get('api/user').then(response => {
                    this.userId = response.data;
                    console.log('UserId');
                    console.log(this.userId);
                })
            },

            setUserLike(){
                for (let j = 0; j < this.placeToShowInPopUp['likes'].length; j++) {
                    if (this.placeToShowInPopUp['likes'][j]['user']['id'] === this.userId) {

                        if (this.placeToShowInPopUp['likes'][j]['value'] === 1) {
                            this.isLike = true;
                            this.isDislike = false;
                        } else {
                            this.isLike = false;
                            this.isDislike = true;
                        }
                    }
                }
            },

            async sendLike(value) {
                if (value === 'like') {
                    value = 1;
                } else value = 0;
                let like = null;
                await axios.post('api/place/'+ this.$route.query.place + '/like', {
                    'value': value
                }).then(response => {
                    like = response.data;
                });
                for (let i = 0; i < this.places.length; i++){
                    if (this.placeToShowInPopUp.id == this.places[i].id){
                        this.places[i].likes.push(like);
                        break;
                    }
                }
            },

            async deleteLike() {
                await axios.delete('api/place/'+ this.$route.query.place +'/like/delete');
                for (let i = 0; i < this.places.length; i++){
                    if (this.placeToShowInPopUp.id === this.places[i].id){
                        for (let j = 0; j < this.places[i].likes.length; j++){
                            if(this.places[i].likes[j].user_id === this.userId){
                                this.places[i].likes.splice(j, 1);
                            }
                        }
                        break;
                    }
                }
            },

            async updateLike(value) {
                if (value === 'like') {
                    value = 1;
                } else value = 0;
                await axios.post('api/place/'+ this.$route.query.place +'/like', {
                    'value': value
                });
                for (let i = 0; i < this.places.length; i++) {
                    if (this.placeToShowInPopUp.id == this.places[i].id){
                        for (let j = 0; j < this.places[i].likes.length; j++){
                            if(this.places[i].likes[j].user_id == this.userId){
                                this.places[i].likes[j].value = value;
                            }
                        }
                        break;
                    }
                }
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
                    this.currentUserRoles = response.data;
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
                for (let i = 0; i < this.places.length; i++){
                    if (this.placeToShowInPopUp.id == this.places[i].id){
                        for (let j = 0; j < this.places[i].comments.length; j++){
                            if(this.places[i].comments[j].id == id){
                                this.places[i].comments.splice(j, 1);
                                console.log('Коментарі');
                                console.log(this.places[i].comments);
                                break;
                            }
                        }
                    }
                }
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
                let comment = null;
                await axios.post('api/place/'+ this.$route.query.place +'/comment', {
                    'body': this.comment
                }).then(response => {
                    comment = response.data;
                });
                for (let i = 0; i < this.places.length; i++){
                    if (this.placeToShowInPopUp.id === this.places[i].id){
                        this.places[i].comments.push(comment);
                        break;
                    }
                }
                this.comment = '';
            },

            checkIfHasTourSlug() {
                for (let i = 0; i < this.tours.length; i++) {
                    if (this.$route.query.tour === this.tours[i]['slug']) {
                        return true;
                    }
                }
                return false;
            },

            checkIfHasPlaceSlug() {
                for (let i = 0; i < this.places.length; i++) {
                    if (this.$route.query.place === this.places[i]['slug']) {
                        return true;
                    }
                }
                return false;
            },

            closeTourPopUp() {
                this.$router.go(-1);
            },

            closePlacePopUp() {
                this.$router.go(-1);
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

            async fetchTours() {
                await axios.get('api/tour/index?page=' + this.paginationTours.current_page).then(response => {
                        console.log('Дані для пагінації');
                        console.log(response.data);
                        this.paginationTours.first_page_url = response.data.first_page_url;
                        this.paginationTours.from = response.data.from;
                        this.paginationTours.last_page = response.data.last_page;
                        this.paginationTours.last_page_url = response.data.last_page_url;
                        this.paginationTours.path = response.data.path;
                        this.paginationTours.per_page = response.data.per_page;
                        this.paginationTours.prev_page_url = response.data.prev_page_url;
                        this.paginationTours.to = response.data.to;
                        this.paginationTours.total = response.data.total;
                        this.tours = response.data.data;
                        console.log('Локальні дані пагінації');
                        console.log(this.paginationTours);
                        console.log('Тури : ');
                        console.log(this.tours);
                    }
                ).catch(error => console.log(error));
            },
        }
    }
</script>
