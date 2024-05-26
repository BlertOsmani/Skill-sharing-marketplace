import { createApp } from "vue";
import "./style.css";
import PrimeVue from "primevue/config";
import App from "./App.vue";
import router from "./router";
import ToastService from "primevue/toastservice";
import ConfirmationService from "primevue/confirmationservice";
//in main.js

//in main.js
import "primevue/resources/themes/aura-dark-green/theme.css";
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";
import "/node_modules/primeflex/primeflex.css";

const app = createApp(App);
app.use(PrimeVue);
app.use(router);
app.use(ToastService);
app.use(ConfirmationService);
app.mount("#app");
