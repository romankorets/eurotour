<template>
    <div id="place-popup" class="b-popup">
        <div class="row justify-content-center b-popup-content">
            <div class="col-md-10">
                <div class="row justify-content-end">
                    <span title="Закрити вікно" class="close"/>
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
</template>

<script>
    export default {
        data: function(){
            return {
                placeToShowInPopUp: null,
                isLike: true,
                isDislike: false,
                userId: null,
            }
        },
        mounted: async function () {
            this.setUserId();
            this.fetchPlaceToShow();
            console.log('Component mounted.');
        },
        methods: {
            async fetchPlaceToShow(){
                axios.get('api/place/' + this.$route.query.place)
                    .then(response => {
                        this.placeToShowInPopUp = response.data;
                        console.log('Відповіть в модал');
                        console.log(response.data);
                    }
                )
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
                this.placeToShowInPopUp.likes.push(like);
            },

            async deleteLike() {
                await axios.delete('api/place/'+ this.$route.query.place +'/like/delete');
                    for (let j = 0; j < this.placeToShowInPopUp.likes.length; j++){
                        if(this.placeToShowInPopUp.likes[j].user_id === this.userId){
                            this.placeToShowInPopUp.likes.splice(j, 1);
                        }
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
                for (let j = 0; j < this.placeToShowInPopUp.likes.length; j++){
                    if(this.placeToShowInPopUp.likes[j].user_id == this.userId){
                        this.placeToShowInPopUp.likes[j].value = value;
                    }
                }
            },
            setUserId(){
                axios.get('api/user').then(response => {
                    this.userId = response.data;
                    console.log('UserId');
                    console.log(this.userId);
                })
            },
    }
</script>
