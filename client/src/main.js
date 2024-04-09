import { createApp } from 'vue';
import './style.css';
import PrimeVue from 'primevue/config';
import App from './App.vue';
import router from './router';
//in main.js

//in main.js
import 'primevue/resources/themes/aura-dark-green/theme.css';
import 'primevue/resources/primevue.min.css';
import 'primeicons/primeicons.css';

const app = createApp(App);
app.use(PrimeVue);
app.use(router);
app.mount('#app');