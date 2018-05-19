
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router';

Vue.use(VueRouter);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const frontPage = Vue.component('front-page', require('./components/FrontPage.vue'));
const frontSidebar = Vue.component('front-sidebar', require('./components/FrontSidebar.vue'));
const frontPhotoList = Vue.component('front-photo-list', require('./components/FrontPhotoList.vue'));
const frontPhotoSingle = Vue.component('front-photo-single', require('./components/FrontPhotoSingle.vue'));

const routes = [
	{
		path: '/',
		component: frontPage
  	},
	{
		path: '/foto/:foto',
		component: frontPhotoSingle
  	}
]

const router = new VueRouter({
	mode: 'history',
	base: '/inicio',
	routes
})

const app = new Vue({
	router
}).$mount('#app')
