<template>
  <div class="w-12 flex">
    <div class="description w-12 flex flex-column justify-content-center align-items-center">
      <div class="w-8 flex flex-column surface-card border-round-md py-2 px-4">
        <h2 class="mb-2">About This Course</h2>
        <p class="mt-2">
          {{ courseDetails.course_description }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

import Fieldset from 'primevue/fieldset';

export default {
  name: 'AboutClass',
  components: {
    Fieldset,
  },
  data() {
    return {
      courseDetails: {
        course_description: ''
      }
    };
  },
  created() {
    this.fetchCourseDetails();
  },
  methods: {
    async fetchCourseDetails() {
      const course_id = this.$route.query.course_id;
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/course/details/${course_id}`);
        this.courseDetails = response.data;
      } catch (error) {
        console.error('Error fetching course details:', error);
      }
    }
  }
};
</script>

<style scoped>
.description {
  padding: 2rem 0;
}

.mb-1 {
  margin-bottom: 1rem;
}

.mt-2 {
  margin-top: 1rem;
}
</style>
