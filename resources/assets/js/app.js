
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.use(require('vue-moment'));

import Toasted from 'vue-toasted';
Vue.use(Toasted, {
    iconPack : 'fontawesome' // set your iconPack, defaults to material. material|fontawesome
})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('bell', require('./components/Bell.vue'));
Vue.component('notification-activity-full', require('./components/NotificationActivityFull.vue'));
Vue.component('noti-activity', require('./components/NotificationActivity.vue'));

const app = new Vue({
    el: '#app'
});
