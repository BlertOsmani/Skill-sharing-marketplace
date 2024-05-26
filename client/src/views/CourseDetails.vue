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
  import CourseIntroduction from '../components/course/CourseIntroduction.vue';
  import StatsCard from '../components/course/StatsCard.vue';
  import LessonList from '../components/course/LessonList.vue'; // Corrected the import statement
  import AboutClass from '../components/course/AboutClass.vue';
  import Review from '../components/course/Review.vue';
  
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
  