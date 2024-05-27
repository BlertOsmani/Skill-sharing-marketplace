<template>
  <div class="mb-5">
    <h2>Continue learning</h2>
    <div class="flex justify-content-start flex-row align-items-center gap-3 overflow-auto">
      <CardStyle1
        v-for="(id, index) in ids"
        :key="index"
        :coverphoto="coverphoto[index]"
        :id="id"
        title="Introduction to Figma"
        :author="author"
        courseInfo="Lesson 15 of 30"
        timeLeft="8m left"
        :progressValue="getProgressValue(index)"
        :maxLength="maxLength"
        :number_of_enrollments="number_of_enrollments"
        :category="category"
      />
    </div>
  </div>
</template>

<script>
import uiUxCourse from '../../assets/images/ui-ux-course.avif';
import uiUxCourse2 from '../../assets/images/ui-ux-course-2.jpg';
import uiUxCourse3 from '../../assets/images/ui-ux-course-3.jpeg';
import CardStyle1 from './CardStyle1.vue';

export default {
  components: {
    CardStyle1,
  },
  data() {
    return {
      ids: [1,2,3],
      coverphoto: [uiUxCourse, uiUxCourse2, uiUxCourse3, uiUxCourse],
      author: 'By Astrit Krasniqi, Blert Osmani, Diart Maraj',
      maxLength: 45,
      progressValues: [50, 70, 10, 30],
      number_of_enrollments: 121,
      category: 'UiUx',
    };
  },
  methods: {
    getProgressValue(index) {
      return this.progressValues[index];
    },
  },
};
</script>