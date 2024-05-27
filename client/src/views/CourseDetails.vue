<template>
    <div>
      <CourseIntroduction />
      <StatsCard />
      <div class="flex w-12 justify-content-center">
        <Divider class="w-8"/>
      </div>
      <LessonList />
      <AboutClass />
      <Review />
    </div>
  </template>
  
  <script>

  import Divider from 'primevue/divider';
  import CourseIntroduction from '../components/course/courseDetails/CourseIntroduction.vue';
  import StatsCard from '../components/course/courseDetails/StatsCard.vue';
  import LessonList from '../components/course/courseDetails/LessonList.vue'; // Corrected the import statement
  import AboutClass from '../components/course/courseDetails/AboutClass.vue';
  import Review from '../components/course/courseDetails/Review.vue';
  
  export default {
    name: 'CourseDetails', // Corrected the syntax error
    components: {
      CourseIntroduction,
      StatsCard,
      LessonList,
      AboutClass,
      Review,
      Divider,
    }
  }
  </script>
  