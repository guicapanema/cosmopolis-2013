
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

Vue.component('photo-edit', require('./components/PhotoEdit.vue'));
Vue.component('poster-edit', require('./components/PosterEdit.vue'));
// Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app'
});
