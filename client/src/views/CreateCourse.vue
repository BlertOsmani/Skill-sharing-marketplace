<template>
  <Stepper class="px-5 my-6">
    <StepperPanel header="Course information">
      <template #content="{ nextCallback }">
        <CourseInformation
          @courseCreated="handleCourseCreated"
          :nextCallback="nextCallback"
        />
      </template>
    </StepperPanel>
    <StepperPanel header="Lessons">
      <template #content="{ prevCallback, nextCallback }">
        <div v-if="courseId">
          <CreateLesson :courseId="courseId" />
        </div>
        <div v-else>Please complete the course information first.</div>
      </template>
    </StepperPanel>
  </Stepper>
</template>

<script setup>
import { ref } from "vue";
import CourseInformation from "../components/course/create/CourseInformation.vue";
import CreateLesson from "../components/course/create/CreateLesson.vue";
import Stepper from "primevue/stepper";
import StepperPanel from "primevue/stepperpanel";
</script>

<script>
export default {
  data() {
    return {
      courseId: null,
    };
  },
  methods: {
    handleCourseCreated(id) {
      this.courseId = id;
      console.log("Course created with ID:", this.courseId);
    },
  },
  mounted(){
    if(!localStorage.getItem('authToken')){
        this.$router.push('/');
    }
  }
};
</script>

<style lang="scss">
.p-grid {
  margin-top: 2rem;
}
.p-stepper-panels{
  border-radius: 0.375rem;
}
</style>
