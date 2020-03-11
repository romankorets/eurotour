<template>
    <div id="place-popup" class="b-popup" v-if="placeToShowInPopUp !== null">
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
                                <img class="d-block w-100" :src="'.' + photo"
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
                <likes-component :likes="placeToShowInPopUp.likes" :id="placeToShowInPopUp.id"></likes-component>
                <div class="row justify-content-center">
                    <div class="rating-mini">
                        <span v-for="(rating,index) in placeToShowInPopUp.rating" class="active"/>
                        <span v-if="placeToShowInPopUp.rating < 10"
                              v-for="(rating,index) in 10 - placeToShowInPopUp.rating"/>
                    </div>
                </div>
                <comments-component :comments="placeToShowInPopUp.comments" :id="placeToShowInPopUp.id"></comments-component>
            </div>
        </div>
    </div>
</template>

import CommentsComponent from './CommentsComponent.vue'
<script>
    export default {
        data: function(){
            return {
                placeToShowInPopUp: null,
            }
        },
        mounted: async function () {
            if(typeof this.$route.query.place !== "undefined"){
                await this.fetchPlaceToShow();

            }
            console.log('Place modal');
        },
        watch:{
            $route: async function(to, from){
                if(typeof this.$route.query.place !== "undefined"){
                    await this.fetchPlaceToShow();

                }
            }
        },

        methods: {
            async fetchPlaceToShow(){
                await axios.get('api/place/' + this.$route.query.place)
                    .then(response => {
                        this.placeToShowInPopUp = response.data;
                        console.log('Відповіть в модал');
                        console.log(response.data);
                    }
                )
            },

            closePlacePopUp() {
                this.placeToShowInPopUp = null;
                this.$router.go(-1);
            },
        },
    }
</script>
