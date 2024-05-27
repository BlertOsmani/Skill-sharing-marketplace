<template>
    <div class="flex justify-content-center flex-column">
      <Lessons  />
      <Review />
    </div>
  </template>
  
  <script>
  import Lessons from '../components/enroll/Lessons.vue';
  import Review from '../components/enroll/Review.vue';
  
  export default {
    components: {
      Lessons,
      Review,
    },
}
    </script> 