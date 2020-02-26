/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import MainPage from "./components/MainPage";

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import PlacesMap from "./components/PlacesMap";
import PlaceModal from "./components/PlaceModal";
Vue.use(VueRouter);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('main-page', require('./components/MainPage.vue').default);
Vue.component('tour-map', require('./components/TourMap.vue').default);
Vue.component('places-map', require('./components/PlacesMap.vue').default);
Vue.component('pagination-vue', require('./components/PaginationTour.vue').default);

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
            //component: MainPage
        },
        {
            path: '/home',
            query: 'place',
            components: {
                placeModal : PlaceModal,
            },
            // children: [{
            //     path: '/home',
            //     query: 'place',
            //     component: PlaceModal,
            // },
            //     {
            //
            //     }]
            //component: MainPage
        }
    ],
});

const app = new Vue({
    router: router,
    el: '#app',
});
