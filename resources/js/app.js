/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */



require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import PlacesMap from "./components/PlacesMap";

Vue.use(VueRouter);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

Vue.component('tour-map', require('./components/TourMap.vue').default);
Vue.component('tour-modal', require('./components/TourModal.vue').default);
Vue.component('places-map', require('./components/PlacesMap.vue').default);
Vue.component('place-modal', require('./components/PlaceModal.vue').default);
Vue.component('comments-component', require('./components/CommentsComponent.vue').default);
Vue.component('likes-component', require('./components/LikesComponent.vue').default);
Vue.component('telegram-auth', require('./components/TelegramAuth.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/home',
            name: 'home',
            components: {
                placesMap : PlacesMap,
            },
        },
    ],
});

const app = new Vue({
    router: router,
    el: '#app',
});
