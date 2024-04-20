// src/router.js
import { createRouter, createWebHistory } from "vue-router";
import Register from "./views/Register.vue";
import Discover from "./views/Discover.vue";
import Login from "./views/Login.vue";

const routes = [
  { path: "/register", component: Register },
  { path: "/", component: Discover },
  { path: "/login", component: Login },
  // Add other routes here
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
