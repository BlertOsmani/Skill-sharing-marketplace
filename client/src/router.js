// src/router.js
import { createRouter, createWebHistory } from 'vue-router';
import Register from './views/Register.vue';
import Discover from './views/Discover.vue';
import Search from './views/Search.vue';

const routes = [
  { path: '/register', component: Register },
  {path : '/', component: Discover},
  {path: '/search', component: Search}
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
