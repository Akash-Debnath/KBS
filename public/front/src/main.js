import Vue from 'vue'
import App from './App.vue'
import router from './router'

//bootstrap
import 'bootstrap'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

// Import Bootstrap an BootstrapVue CSS files (order is important)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

//axios
import axios from 'axios'
import VueAxios from 'vue-axios'

//custom-css
import '@/assets/fonts/nunito/font.css'
import '@/assets/css/bundle.css'
import '@/assets/css/style.css'
import '@/assets/css/custom.css'
import '@/assets/fonts/nunito/Nunito-Regular.ttf'

 axios.defaults.baseURL = 'http://localhost/ificKBS/public/api/';
// axios.defaults.baseURL = 'http://cc-softrnd.gplex.net/kbs/back/public/api/';
// axios.defaults.baseURL = 'https://kbs.gplex.com/back/public/api/';
// axios.defaults.baseURL  = 'http://127.0.0.1:8000/api/';
//axios.defaults.baseURL = 'http://192.168.11.53/kbs/back/public/api/';
//axios.defaults.baseURL = 'http://192.168.11.53/kbspro/back/public/api/';

Vue.config.productionTip = false

new Vue({
  router,
  BootstrapVue,
  IconsPlugin,
  axios,
  VueAxios,
  render: h => h(App)
}).$mount('#app')
