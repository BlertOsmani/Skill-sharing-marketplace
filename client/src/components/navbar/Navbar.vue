<template lang="">
  <div
    class="surface-card flex flex-row justify-content-between align-items-center p-2 gap-1 sticky top-0 shadow-2 z-1 h-auto"
  >
    <div class="logo w-1 flex px-2 flex-row align-items-center">
      <Logo />
    </div>
    <div
      class="links flex flex-row align-items-center w-auto justify-content-start"
    >
      <Links />
    </div>
    <div class="w-12">
      <SearchInput />
    </div>
    <div
      class="links flex flex-row align-items-center justify-content-end w-4 relative"
    >
      <Button
        severity="primary"
        label="Create course"
        class="h-2point5rem px-3 border-round-md"
      ></Button>
    </div>
    <div class="w-auto flex justify-content-end">
      <Profile />
    </div>
  </div>
</template>
<script>
import Links from "./Links.vue";
import Button from "primevue/button";
import Logo from "./Logo.vue";
import Profile from "./Profile.vue";
import SearchInput from "../SearchInput.vue";
export default {
  components: {
    Logo,
    Button,
    Links,
    Profile,
    SearchInput,
  },
};
</script>
<style lang="css"></style>
