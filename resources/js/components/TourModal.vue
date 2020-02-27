<template>
    <div id="tour-popup" class="b-popup" v-if="tourToShow !== null">
        <div class="row justify-content-center b-popup-content">
            <div class="col-md-10">
                <div class="row justify-content-end">
                    <span title="Закрити вікно" class="close" v-on:click="closeTourPopUp"/>
                </div>
                <div class="row justify-content-center">
                    <h1>Карта</h1>
                </div>
                <div class="row justify-content-center">
                    <tour-map :tour="tourToShow"/>
                </div>
                <div class="row justify-content-center">
                    <h1>{{ tourToShow.name }}</h1>
                </div>
                <div class="row justify-content-around">
                    <div class="col-md-4">
                        <div class="row justify-content-center">
                            <div :id="'carouselControls' + tourToShow.id" class="carousel slide"
                                 data-ride="false">
                                <div class="carousel-inner">
                                    <div class="carousel-item" :class="{active : index===0}"
                                         v-for="(photo, index) in tourToShow.photos">
                                        <img class="d-block w-100" :src="'./storage/' + photo"
                                             :alt="tourToShow.name">
                                    </div>
                                    <a class="carousel-control-prev"
                                       :href="'#carouselControls' + tourToShow.id"
                                       role="button"
                                       data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"/>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next"
                                       :href="'#carouselControls' + tourToShow.id"
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
                            <article>{{ tourToShow.description }}</article>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                tourToShow: null,
            }
        },
        mounted() {
            if (typeof this.$route.query.tour !== "undefined"){
                this.fetchTourToShow();
            }
            console.log('Component mounted.')
        },

        watch:{
            $route: function(to, from){
                if (typeof to.query.tour !== "undefined"){
                    this.fetchTourToShow();
                }
            }
        },
        methods: {
            closeTourPopUp() {
                this.tourToShow = null;
                this.$router.go(-1);
            },

            async fetchTourToShow() {
                await axios.get('api/tour/' + this.$route.query.tour)
                    .then(response => {
                            this.tourToShow = response.data;
                            console.log('Відповіть в модал');
                            console.log(response.data);
                        }
                    )
            },
        }
    }
</script>
