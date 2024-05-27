<template lang="">
  <div class="flex w-custom-card surface-card border-round-md mb-4">
    <CourseCardED
      :id="id"
      :thumbnail="thumbnail"
      :title="title"
      :category="category"
      :author="author"
      :info="info"
      :duration="duration"
      :enrolled="enrolled"
      @requireConfirmation="$emit('requireConfirmation', $event)"
      @openEditDialog="$emit('openEditDialog', $event)"
      @viewLessons="$emit('viewLessons', $event)"
    />
  </div>
</template>
<script>
import CourseCardED from "../../CourseCardED.vue";
export default {
  components: {
    CourseCardED,
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
};
</script>
<style lang=""></style>
