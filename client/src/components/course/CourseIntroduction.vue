<template>
  <div class="px-5 py-4 mb-6 surface-50">
    <div class="w-12 flex flex-row justify-content-between">
      <div class="flex flex-row w-10 gap-3">
      <div class="w-5">
        <video controls class="w-full border-round-md" v-if="course_video">
          <source :src="course_video" type="video/mp4" />
          Your browser does not support the video tag.
        </video>
      </div>
      <div class="w-auto flex align-items-center">
        <div>
          <h2 class="text-4xl mb-2">{{ courseDetails.course_title }}</h2>
          <div class="flex align-items-center">
            <Avatar :image="authorAvatar" class="mr-2" size="medium" shape="circle" />
            <p class="text-lg">{{ courseDetails.author }}</p>
          </div>
        </div>
      </div>
      </div>
      <div class="w-auto">
        <Button label="Start Now" @click="enroll"></Button>
      </div>
    </div>
  </div>
</template>

<script>
import video from '../../assets/video/video.mp4';
import Avatar from 'primevue/avatar';
import Button from 'primevue/button';
import axios from 'axios';

export default {
  components: {
    Avatar,
    Button,
  },
  data() {
    return {
      courseDetails: {},
      authorAvatar: 'https://www.gravatar.com/avatar/placeholder?d=mp',
      course_video : video,
    };
  },
  created() {
    this.fetchCourseDetails();
  },
  methods: {
    async fetchCourseDetails() {
      var course_id = this.$route.query.course_id
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/course/details?course_id=${course_id}`, {
        });
        this.courseDetails = response.data;
        this.authorAvatar = response.data.author_avatar || this.authorAvatar; // Assuming the avatar URL is provided
      } catch (error) {
        console.error('Error fetching course details:', error);
      }
    },
    async enroll(){
      this.$router.push('/course');
    }
  }
};
</script>

<style lang="css">
.text-black {
  color: black;
}
.p-4 {
  padding: 1rem;
}
.text-4xl {
  font-size: 2.25rem;
}
.mb-2 {
  margin-bottom: 0.5rem;
}
.text-lg {
  font-size: 1.125rem;
}
.w-full {
  width: 100%;
}
.flex {
  display: flex;
}
.align-items-center {
  align-items: center;
}
</style>
