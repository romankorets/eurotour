<template>
    <div class="container">
        <div v-for="item in places">
            <div>{{item.name}}</div>
            <div>{{item.description}}</div>
            <div>{{item.rating}}</div>
            <div>{{item.lat}}</div>
            <div>{{item.lng}}</div>
            <img v-for="photo in item.photos" :src="'./storage/' + photo">
        </div>
    </div>
</template>

<script>
    export default {
        data: function(){
            return{
                places: null,
                photos: []
            }
        },
        mounted() {
            axios.get('api/place/index').then(response => {
                    this.places = JSON.parse(response.data);
                    console.log(this.places);
                    console.log(JSON.parse(this.places[0].photos));
                    for (let i = 0; i < this.places.length; i++){
                        this.places[i].photos = JSON.parse(this.places[i].photos);
                    }
                }
            ).catch(error => console.log(error));
        }
    }
</script>
