<template>
    <div class="px-5 my-6">
      <h2>{{ albumTitle }}</h2>
        <div class="flex justify-content-start overflow-auto flex-wrap flex-row gap-3">
            <Courses v-for="(course, index) in courses"
              :key="course.id"
              :id="course.id"
              :thumbnail="course.thumbnail"
              :title="course.title"
              :category="course.category"
              :author="course.author"
              :info="course.info"
              :duration="course.duration"
              :enrolled="course.enrolled"
            />
        </div>
    </div>
  </template>
  
  <script>
  import Courses from '../components/saved/Courses.vue';
  
  export default {
    components: {
      Courses,
    },
    props: {
      id: Number,
      thumbnail: String,
      title: String,
      category: String,
      author: String,
      info: String,
      duration: String,
      enrolled: String,
    },
    data() {
      return {
        courses: [],
        albumTitle: '',
        albumId: '',
      };
    },
    methods: {
      async getSavedCourses() {
        try {
          const response = await fetch(`http://127.0.0.1:8000/api/album/saved/get?albumId=${this.albumId}`, {
            method: 'GET',
            headers: {
              'Content-Type': 'application/json',
              'Access-Control-Allow-Origin': '*',
            },
            mode: 'cors',
          });
          if (!response.ok) {
            throw new Error('Something went wrong fetching the saved courses. Please refresh the page!');
          }
          const data = await response.json();
          this.courses = data.map(course => {
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
                      info: info
                  }; 
              });
        } catch (error) {
          console.error(error.message);
        }
      },
    },
    created(){
      this.albumTitle = this.$route.query.name; 
      this.albumId = this.$route.query.id;
    },
    mounted() { // Get the albumTitle from the route params
      this.getSavedCourses();
    },
  }
  </script>
  
  <style lang="">
  </style>
  