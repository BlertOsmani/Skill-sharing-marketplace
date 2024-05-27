<template>
  <div class="mb-5">
    <div class="flex flex-row align-items-center justify-content-between">
      <h2>Featured</h2>
      <Button severity="contrast" label="View all" outlined></Button>
    </div>
    <div class="flex justify-content-start flex-row align-items-center gap-3 overflow-auto">
      <CardStyle3
        v-for="(card, index) in cards"
        :key="index"
        :thumbnail="card.thumbnail"
        :title="card.title"
        :id="card.id"
        :author="card.author"
        :maxLength="maxLength"
        :enrolled="card.enrolled"
        :category="card.category"
        :duration="card.duration"
        :info="card.info"
        :price="card.price"
      />
    </div>
  </div>
</template>

<script>
import uiUxCourse from '../../assets/images/ui-ux-course.avif';
import uiUxCourse2 from '../../assets/images/ui-ux-course-2.jpg';
import uiUxCourse3 from '../../assets/images/ui-ux-course-3.jpeg';
import CardStyle3 from './CardStyle3.vue';
import Carousel from 'primevue/carousel';
import Button from 'primevue/button';

export default {
  components: {
    CardStyle3,
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
      async getFeaturedCourses(){
          const limit = 4;
          try{
              var response = await fetch(`http://127.0.0.1:8000/api/course/featured?limit=${limit}`, {
                  method: 'GET',
                  headers: {
                      'Content-Type': 'application/json',
                      'Access-Control-Allow-Origin': '*',
                  },
                  mode: 'cors'
              });
              if(!response.ok){
                  throw new Error("Something went wrong. Try again!");
              }
              const courses = await response.json();
              this.cards = courses.map(course => {
                  const lessons = course.number_of_lessons;
                  const tags = course.course_tags;
                  let info = '';

                  if (!lessons || lessons.length === 0) {
                      info = tags || '';
                  } else if (!tags || tags.length === 0) {
                      info = lessons;
                  } else {
                      info = lessons; // Default to lessons if both are present
                  }

                  return {
                      id: course.course_id,
                      title: course.course_title,
                      category: course.category_name,
                      author: course.author,
                      thumbnail: course.course_thumbnail,
                      enrolled: `${course.number_of_enrollments} enrolled`,
                      lessons: lessons,
                      tags: tags,
                      price: course.course_price,
                      info: info
                  }; 
              });
          }catch(error){
              console.log('Error fetching learning data:', error);
          }
    },
  },
  async mounted(){
    await this.getFeaturedCourses();
  }
};
</script>

<style lang="css">
</style>