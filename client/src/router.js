// src/router.js
import { createRouter, createWebHistory } from 'vue-router';
import Register from './views/Register.vue';
import Discover from './views/Discover.vue';
import ForgotPassword from './views/ForgotPassword.vue';
import ResetPassword from './views/ResetPassword.vue';


const routes = [
  { path: '/register', component: Register },
  {path : '/', component: Discover},
  { path : '/forgotpassword', component: ForgotPassword},
  { path : '/resetpassword', component: ResetPassword}
  // Add other routes here
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
