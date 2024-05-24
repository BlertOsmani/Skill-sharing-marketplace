// src/router.js
import { createRouter, createWebHistory } from 'vue-router';
import Register from './views/Register.vue';
import Discover from './views/Discover.vue';
import Search from './views/Search.vue';
import Saved from './views/Saved.vue';

const routes = [
  { path: '/register', component: Register },
  {path : '/', component: Discover},
  {path: '/search', component: Search},
  {path: '/saved', component: Saved}
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
