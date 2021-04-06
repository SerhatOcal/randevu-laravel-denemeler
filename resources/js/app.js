/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import VueMask from 'v-mask';


window.Vue = require('vue').default;
Vue.use(VueMask);
Vue.use(require('vue-resource'));

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('admin-randevu-listesi', require('./components/Admin/AdminRandevuListesiComponenet').default);
Vue.component('admin-component', require('./components/Admin/AdminComponent').default);
Vue.component('admin-calisma-saatleri', require('./components/Admin/AdminCalismaSaatleriComponent').default);
Vue.component('admin-calisma-saatleri-gun', require('./components/Admin/AdminCalismaSaatleriGunComponent').default);
Vue.component('admin-randevu-modal', require('./components/Admin/AdminRandevuDetayModal').default);


Vue.component('randevu-form', require('./components/RandevuFormComponent.vue').default);
Vue.component('randevu-detay', require('./components/RandevuDetayComponent.vue').default);
Vue.component('pagination', require('laravel-vue-pagination'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
