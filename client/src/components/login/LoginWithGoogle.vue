<template>
  <div class="register-with-google-button-container sm:w-12 md:w-8 lg:w-6">
    <Button
      severity="secondary"
      icon="pi pi-google"
      label="Sign in with Google"
      class="w-full"
    ></Button>
  </div>
</template>

<script>
import Button from "primevue/button";
export default {
  components: {
    Button,
  },
};
</script>

<style lang="scss">
.register-with-google-button-container {
  display: flex;
  justify-content: center;
  margin: 0.5rem 0;
}

.register-with-google-button-container Button {
  padding: 12px;
}
</style>
