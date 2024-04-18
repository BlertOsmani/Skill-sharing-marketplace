// src/router.js
import { createRouter, createWebHistory } from 'vue-router';
import Register from './views/Register.vue';
import Discover from './views/Discover.vue';

const routes = [
  { path: '/register', component: Register },
  {path : '/', component: Discover}
  // Add other routes here
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
