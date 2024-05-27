import { createRouter, createWebHistory } from "vue-router";
import Login from "./views/Login.vue";
import Register from "./views/Register.vue";
import Discover from "./views/Discover.vue";
import ForgotPassword from "./views/ForgotPassword.vue";
import ResetPassword from "./views/ResetPassword.vue";
import Search from "./views/Search.vue";
import Albums from "./views/Albums.vue";
import Lessons from './views/Lessons.vue';
import AlbumCourses from "./views/AlbumCourse.vue";
import CreateCourse from "./views/CreateCourse.vue";
import Categories from "./views/Categories.vue";

const routes = [
  { path: "/register", component: Register },
  { path: "/", component: Discover },
  { path: "/login", component: Login },
  { path: "/search", component: Search },
  { path: "/saved", component: Albums },
  { path: "/saved/album", component: AlbumCourses },
  { path: "/forgotpassword", component: ForgotPassword },
  { path: "/resetpassword", component: ResetPassword },
  { path: "/course/create", component: CreateCourse },
  { path : '/enroll', component: Lessons},
  {path: '/categories', component: Categories}
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
