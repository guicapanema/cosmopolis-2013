
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router';
import VueCookies from 'vue-cookies';

Vue.use(VueCookies);
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
const frontPosterList = Vue.component('front-poster-list', require('./components/FrontPosterList.vue'));

const routes = [
	{
		path: '/',
		component: frontPage,
		children: [
			{
				path: '/foto/:foto',
				component: frontPhotoSingle
		  	},
			{
				path: '/cartazes',
				component: frontPage
		  	}
		]
  	}
]

const router = new VueRouter({
	mode: 'history',
	base: '/principal',
	routes
})

const app = new Vue({
	router
}).$mount('#app')
