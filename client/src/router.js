

// src/router.js
import { createRouter, createWebHistory } from 'vue-router';
import Register from './views/Register.vue';
import Login from "./views/Login.vue";
import Discover from './views/Discover.vue';
import ForgotPassword from './views/ForgotPassword.vue';
import ResetPassword from './views/ResetPassword.vue';
import Search from './views/Search.vue';
import Lessons from './views/Lessons.vue';
import CourseDetails from './views/CourseDetails.vue';
import Albums from './views/Albums.vue';
import AlbumCourses from './views/AlbumCourse.vue';
import CreateCourse from "./views/CreateCourse.vue";
import MyCourses from "./views/MyCourses.vue";
import CourseLessons from "./views/CourseLessons.vue";
import Categories from "./views/Categories.vue";


const routes = [
  { path: '/signup', component: Register },
  {path : '/', component: Discover},
  {path: '/search', component: Search},
  { path: "/signin", component: Login },
  {path: '/course/details', component: CourseDetails},
  {path: '/saved', component: Albums},
  {path: '/saved/album', component: AlbumCourses},
  { path : '/forgotpassword', component: ForgotPassword},
  { path : '/resetpassword', component: ResetPassword},
  { path: "/course/create", component: CreateCourse },
  { path: "/my-courses", component: MyCourses },
  {
    path: "/my-courses/lessons",
    name: "CourseLessons",
    component: CourseLessons,
  },
  { path : '/course/enroll', component: Lessons},
  {path: '/categories', component: Categories}

];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
