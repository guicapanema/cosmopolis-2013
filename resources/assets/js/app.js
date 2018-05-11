
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Masonry = require('masonry-layout');
window.moment = require('moment');
window.Vue = require('vue');
import Buefy from 'buefy';
import 'buefy/lib/buefy.css';

Vue.use(Buefy, {defaultIconPack: 'fas'});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('poster-table', require('./components/PosterTable.vue'));
Vue.component('photo-table', require('./components/PhotoTable.vue'));
Vue.component('poster-edit', require('./components/PosterEdit.vue'));
Vue.component('photo-edit', require('./components/PhotoEdit.vue'));
Vue.component('photo-poster-card', require('./components/PhotoPosterCard.vue'));
// Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app'
});
