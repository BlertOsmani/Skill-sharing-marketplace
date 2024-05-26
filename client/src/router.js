import { createRouter, createWebHistory } from "vue-router";
import Register from "./views/Register.vue";
import Discover from "./views/Discover.vue";
import ForgotPassword from "./views/ForgotPassword.vue";
import ResetPassword from "./views/ResetPassword.vue";
import Search from "./views/Search.vue";
import Albums from "./views/Albums.vue";
import AlbumCourses from "./views/AlbumCourse.vue";
import CreateCourse from "./views/CreateCourse.vue";

const routes = [
  { path: "/register", component: Register },
  { path: "/", component: Discover },
  { path: "/search", component: Search },
  { path: "/saved", component: Albums },
  { path: "/saved/album", component: AlbumCourses },
  { path: "/forgotpassword", component: ForgotPassword },
  { path: "/resetpassword", component: ResetPassword },
  { path: "/course/create", component: CreateCourse },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
