<template>
    <div class="mb-5">
      <div class="flex flex-row align-items-center justify-content-between">
        <h2>Enrolled in</h2>
        <Button severity="contrast" label="View all" outlined></Button>
      </div>
      <div class="flex justify-content-start flex-row align-items-center gap-3 overflow-auto">
        <CardStyle2
          v-for="(card, index) in cards"
          :key="index"
          :coverPhoto="card.coverPhoto"
          :title="card.title"
          :id="card.id"
          :author="card.author"
          :username="card.username"
          :courseInfo="card.courseInfo"
          :maxLength="maxLength"
          :numberOfEnrollments="card.numberOfEnrollments"
          :category="card.category"
          :duration="card.duration"
        />
      </div>
    </div>
  </template>
  
<script>
  import uiUxCourse from '../../assets/images/ui-ux-course.avif';
  import uiUxCourse2 from '../../assets/images/ui-ux-course-2.jpg';
  import uiUxCourse3 from '../../assets/images/ui-ux-course-3.jpeg';
  import CardStyle2 from './CardStyle2.vue';
  import Carousel from 'primevue/carousel';
  import Button from 'primevue/button';
  
  export default {
    components: {
      CardStyle2,
      Carousel,
      Button,
    },
    data() {
      return {
        cards:[],
        maxLength: 45,
      };
    },
    methods: {
      async getEnrolledCourses() {
        const limit = 4;
        try {
          const response = await fetch(`http://127.0.0.1:8000/api/course/enrolled?limit=${limit}`, {
            method: 'GET',
            headers: {
              'Content-Type': 'application/json',
              'Access-Control-Allow-Origin': '*',
            },
            mode: 'cors'
          });
          if (!response.ok) {
            throw new Error('Something went wrong fetching the courses you are enrolled in. Please refresh the page!');
          }
          const data = await response.json();
          // Assuming the response data structure matches the card data structure
          this.cards = data.map(course => ({
            id: course.course_id,
            coverPhoto: course.course_thumbnail, // Adjust based on your data
            title: course.course_title,
            author: course.course_author,
            courseInfo: course.course_tags, // Adjust based on your data
            progressValue: 10,
            numberOfEnrollments: course.enrollments_count,
            category: course.category_name,
            duration: "4h 8min",
          }));
        } catch (error) {
          console.error('Error fetching learning data:', error);
        }
    },
    },
    mounted(){
      this.getEnrolledCourses();
    }
  };
  </script>

<style lang="css">
</style>