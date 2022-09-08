import { createApp } from 'vue';
import ElementPlus from  'element-plus';
import './assets/theme-chalk/src/index.css';
import App from './App.vue';
import router from './router';
import './assets/scss/bootstrap.css';
import 'bootstrap';
const app = createApp(App)
app.use(ElementPlus)
app.use(router)

app.mount('#app')
