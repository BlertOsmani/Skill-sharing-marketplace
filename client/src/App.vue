<script setup>
import { ref } from 'vue';

const value = ref(null);
</script>
      
<template>
  <div class="card flex justify-content-center">
      <InputText type="text" v-model="value" />
  </div>
</template>

<style scoped>
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}
</style>
