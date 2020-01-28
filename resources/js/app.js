require('./bootstrap');

window.Vue = require('vue');

import App from '../vue/App.vue';

const app = new Vue({
  el: '#app',
  components: {
    App
  },
  render: h => h(App)
});