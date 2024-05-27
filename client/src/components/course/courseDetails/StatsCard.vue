<template>
  <div class="w-12 grid align-items-center justify-content-center">
    <div class="card col-12 md:col-4 w-1">
      <div class="surface-card p-4 border-round-md shadow-2 flex flex-column align-items-center">
        <i class="pi pi-chart-bar text-5xl mb-2"></i>
        <span class="text-lg">{{ courseDetails.course_level }}</span>
      </div>
    </div>
    <div class="card col-12 md:col-4 w-1">
      <div class="surface-card p-4 border-round-md shadow-2 flex flex-column align-items-center">
        <span class="text-4xl mb-2">{{ courseDetails.number_of_enrollments }}</span>
        <span class="text-lg">Enrollment</span>
      </div>
    </div>
    <div class="card col-12 md:col-4 w-1">
      <div class="surface-card p-4 border-round-md shadow-2 flex flex-column align-items-center">
        <span class="text-4xl mb-2">{{ courseDetails.number_of_lessons }}</span>
        <span class="text-lg">Lessons</span>
      </div>
    </div>
    <div class="card col-12 md:col-4 w-1">
      <div class="surface-card p-4 border-round-md shadow-2 flex flex-column align-items-center">
        <i class="pi pi-star text-5xl mb-2"></i>
        <span class="text-lg">{{ courseDetails.average_rating }}</span>
      </div>
    </div>
    <div class="card col-12 md:col-4 w-1">
      <div class="surface-card p-4 border-round-md shadow-2 flex flex-column align-items-center">
        <span class="text-4xl mb-2">{{ courseDetails.course_price }}€</span>
        <span class="text-lg">Price</span>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      courseDetails: {
        number_of_enrollments: 0,
        number_of_lessons: 0,
        average_rating: 0,
        course_price: 0,
        course_level: '--'
      }
    };
  },
  created() {
    this.fetchCourseDetails();
  },
  methods: {
    async fetchCourseDetails() {
      var course_id = this.$route.query.course_id;
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/course/details?course_id=${course_id}`);
        this.courseDetails = response.data;
      } catch (error) {
        console.error('Error fetching course details:', error);
      }
    }
  }
};
</script>

<style lang="css">
.container {
  
  padding-top: 1rem;
}
.surface-card {
  background-color: white;
}
.border-round {
  border-radius: 0.5rem;
}
.shadow-2 {
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.flex {
  display: flex;
}
.flex-column {
  flex-direction: column;
}
.align-items-center {
  align-items: center;
}
.p-4 {
  padding: 1rem;
}
.mb-2 {
  margin-bottom: 0.5rem;
}
.text-4xl {
  font-size: 2.25rem;
}
.text-2xl {
  font-size: 1.5rem;
}
.text-lg {
  font-size: 1.125rem;
}
</style>
