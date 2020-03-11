<template>
    <div>
        <div class="row justify-content-around like">
            <i class="fa fa-thumbs-up" v-on:click="toggleLike('like')" :class="{'fa-pressed': isLike}"/>
            <i class="fa fa-thumbs-down" v-on:click="toggleLike('dislike')"
               :class="{'fa-pressed': isDislike}"/>
        </div>
        <div class="row justify-content-around count">
            <span>{{this.numberOfLikes}}</span>
            <span>{{this.numberOfDislikes}}</span>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['likes', 'id'],
        data() {
            return{
                localLikes: this.likes,
                userId: null,
                isLike: false,
                isDislike: false,
            }
        },
        created: async function () {

        },
        mounted: async function () {
            await this.setUserId();
            this.setUserLike();
            this.listenLikes();
            console.log('likes component is mounted');
        },

        computed: {
            numberOfLikes: function () {
                let counter = 0;
                for (let i = 0; i < this.localLikes.length; i++) {
                    if (this.localLikes[i]['value'] === 1) {
                        counter++;
                    }
                }
                return counter;
            },
            numberOfDislikes: function () {
                let counter = 0;
                for (let i = 0; i < this.localLikes.length; i++) {
                    if (this.localLikes[i]['value'] === 0) {
                        counter++;
                    }
                }
                return counter;
            }
        },

        methods: {

            listenLikes() {
                window.Echo.channel(`place.${this.id}.likes`)
                    .listen('.like.new', (e) => {
                        let updated = false;
                        for (let j = 0; j < this.localLikes.length; j++) {
                            if (this.localLikes[j].id === e.like.id) {
                                this.localLikes[j].value = e.like.value;
                                updated = true;
                                break;
                            }
                        }
                        if (updated === false) {
                            this.localLikes.push(e["like"]);
                            console.log('Event like');
                            console.log(e);
                        }
                    })
                    .listen('.like.delete', (e) => {
                        for (let j = 0; j < this.localLikes.length; j++) {
                            if (this.localLikes[j].id === e.like.id) {
                                this.localLikes.splice(j, 1);
                                break;
                            }
                        }
                    });
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
                    this.localLikes.push(like);
                });

            },

            async deleteLike() {
                await axios.delete('api/place/' + this.$route.query.place + '/like');
                for (let j = 0; j < this.localLikes.length; j++) {
                    if (this.localLikes[j].user_id === this.userId) {
                        this.localLikes.splice(j, 1);
                    }
                }
            },
            async updateLike(value) {
                if (value === 'like') {
                    value = 1;
                } else value = 0;
                await axios.post('api/place/' + this.$route.query.place + '/like', {
                    'value': value
                });
                for (let j = 0; j < this.localLikes.length; j++) {
                    if (this.localLikes[j].user_id === this.userId) {
                        this.localLikes[j].value = value;
                    }
                }
            },

            toggleLike(value) {
                if (!this.isLike && !this.isDislike) {  // create like if it doesn't exist
                    this.sendLike(value);
                    this.isLike = value === 'like';
                    this.isDislike = value === 'dislike';
                } else {
                    if (value === 'like') {
                        if (this.isLike) {
                            this.deleteLike();
                            this.isLike = false;
                        } else {
                            this.updateLike(value);
                            this.isLike = true;
                            this.isDislike = false;
                        }
                    } else {
                        if (this.isDislike) {
                            this.deleteLike();
                            this.isDislike = false;
                        } else {
                            this.updateLike(value);
                            this.isLike = false;
                            this.isDislike = true;
                        }
                    }
                }
            },

            setUserLike(){
                for (let j = 0; j < this.localLikes.length; j++) {
                    if (this.localLikes[j]['user']['id'] === this.userId) {
                        if (this.localLikes[j]['value'] === 1) {
                            this.isLike = true;
                            this.isDislike = false;
                            break;
                        } else {
                            this.isLike = false;
                            this.isDislike = true;
                            break;
                        }
                    }
                }
            },

            async setUserId(){
                await axios.get('api/user').then(response => {
                    this.userId = response.data;
                    console.log('UserId');
                    console.log(this.userId);
                })
            },
        }
    }
</script>
