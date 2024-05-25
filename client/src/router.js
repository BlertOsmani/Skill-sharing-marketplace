// src/router.js
import { createRouter, createWebHistory } from 'vue-router';
import Register from './views/Register.vue';
import Discover from './views/Discover.vue';
import Search from './views/Search.vue';
import Albums from './views/Albums.vue';
import AlbumCourses from './views/AlbumCourse.vue';

const routes = [
  { path: '/register', component: Register },
  {path : '/', component: Discover},
  {path: '/search', component: Search},
  {path: '/saved', component: Albums},
  {path: '/saved/album', component: AlbumCourses},
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
