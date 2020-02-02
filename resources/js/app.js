require('./bootstrap');
require('./debugging');

window.Vue = require('vue');

import App from '../vue/App.vue';
import VModal from 'vue-js-modal';
import vSelect from 'vue-select'
import VueAnime from 'vue-animejs';

Vue.use(VModal);
Vue.use(VueAnime);
Vue.component('v-select', vSelect);

const app = new Vue({
  el: '#app',
  components: {
    App
  },
  render: h => h(App)
});