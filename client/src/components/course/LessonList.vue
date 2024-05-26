<template>
  <div class="container w-12 align-items-center justify-content-center flex">
    <div class=" w-8 justify-content-center align-items-center">
      <div class="flex justify-content-between align-items-center my-3">
        <h2>Lessons in This Class</h2>
        <span>{{ numberOfLessons }} Lessons ({{ formattedTotalDuration }})</span>
      </div>
      <div class="list-group">
        <div 
          v-for="(lesson, index) in lessons" 
          :key="index" 
          class="list-group-item surface-card"
          :class="{'active-lesson': activeLesson === index}">
          <div class="flex justify-content-between align-items-center">
            <div class="flex align-items-center">
              <i></i>
              <i></i>
              <span>{{ index + 1 }}. {{ lesson.title }}</span>
            </div>
            <span>{{ lesson.duration }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'LessonList',
  data() {
    return {
      lessons: [],
      numberOfLessons: 0,
      totalLessonDuration: 0
    };
  },
  computed: {
    formattedTotalDuration() {
      const totalMinutes = Math.floor(this.totalLessonDuration);
      const hours = Math.floor(totalMinutes / 60);
      const minutes = totalMinutes % 60;
      return `${hours > 0 ? hours + 'h ' : ''}${minutes}m`;
    }
  },
  created() {
    this.fetchCourseDetails();
  },
  methods: {
    async fetchCourseDetails() {
      const course_id = this.$route.query.course_id;
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/course/details?course_id=${course_id}`);
        const courseDetails = response.data;
        this.lessons = courseDetails.lessons;
        this.numberOfLessons = courseDetails.number_of_lessons;
        this.totalLessonDuration = courseDetails.total_lesson_duration;
      } catch (error) {
        console.error('Error fetching course details:', error);
      }
    }
  }
};
</script>

<style scoped>
.container {
  padding-top: 1rem;
  background-color: black;
}

.list-group {
  border: 1px solid #e0e0e0;
  border-radius: 0.5rem;
  overflow: hidden;
}

.list-group-item {
  padding: 1rem;
  border-bottom: 1px solid white;
  color: white;

}

.list-group-item:last-child {
  border-bottom: none;
}

.list-group-item.active-lesson {
  background-color: #031A3D;
  color: white;
}

.flex {
  display: flex;
}

.align-items-center {
  align-items: center;
}

.justify-content-between {
  justify-content: space-between;
}

.mr-2 {
  margin-right: 0.5rem;
}

.mb-3 {
  margin-bottom: 1rem;
}

.p-4 {
  padding: 1rem;
}

.w-8 {
  width: 50%; /* Adjust as needed */
}
</style>
