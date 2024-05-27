
import { createApp } from "vue";
import "./style.css";
import PrimeVue from "primevue/config";
import App from "./App.vue";
import router from "./router";
import ToastService from "primevue/toastservice";
import ConfirmationService from "primevue/confirmationservice";


// Import PrimeVue and PrimeFlex CSS files
import 'primevue/resources/themes/aura-dark-green/theme.css'; // Choose your theme
import 'primeicons/primeicons.css';
import '/node_modules/primeflex/primeflex.css';
import 'primevue/resources/primevue.min.css';
import axios from 'axios';

axios.defaults.baseURL = 'http://localhost:8000/api/';


// Create the app and configure PrimeVue, router, and ToastService
const app = createApp(App);
app.use(PrimeVue);
app.use(router);
app.use(ToastService);
app.use(ConfirmationService);
app.mount("#app");
