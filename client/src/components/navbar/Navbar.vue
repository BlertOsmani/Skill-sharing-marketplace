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
    <div class="flex flex-row align-items-center w-4 justify-content-end" v-if="!isAuthenticated">
        <div
            class="links flex flex-row align-items-center justify-content-end w-relative"
        >
            <router-link to="/signin" class="links flex flex-row align-items-center justify-content-end w-12 relative">
            <Button
                severity="contrast"
                text
                label="Sign in"
                class="h-2point5rem px-3 border-round-md"
            ></Button>
            </router-link>
        </div>
        <div
             class="links flex flex-row align-items-center justify-content-end w-4 relative"
        >
        <router-link to="/signup" class="links flex flex-row align-items-center justify-content-end w-12 relative">
            <Button
                severity="primary"
                label="Sign up"
                class="h-2point5rem px-3 border-round-md"
            ></Button>
            </router-link>
        </div>
    </div>
    <div
      v-if="isAuthenticated" class="links flex flex-row align-items-center justify-content-end w-4 relative"
    >
       <router-link to="/course/create" class="links flex flex-row align-items-center justify-content-end w-auto relative">
        <Button
            severity="primary"
            label="Create course"
            class="h-2point5rem px-3 border-round-md"
        ></Button>
        </router-link>
    </div>
    <div v-if="isAuthenticated" class="w-auto flex justify-content-end">
      <Profile/>
    </div>
  </div>
</template>
<script>
import { ref, onMounted, watch } from 'vue';
import Links from "./Links.vue";
import Button from "primevue/button";
import Logo from "./Logo.vue";
import Profile from "./Profile.vue";
import SearchInput from "../SearchInput.vue";
import AuthServices from "@/services/AuthServices";
export default {
  components: {
    Logo,
    Button,
    Links,
    Profile,
    SearchInput,
  },
  setup() {
    const isAuthenticated = ref(localStorage.getItem('authToken'));

    onMounted(async () => {
      const user = await AuthServices.getProfile();
      if (user) {
        console.log(isAuthenticated);
        isAuthenticated.value = true;
      } else {
        isAuthenticated.value = false;
        localStorage.removeItem('authToken');
      }
    });

    watch(isAuthenticated, (newValue) => {
      if (!newValue) {
        localStorage.removeItem('authToken');
      }
    });

    return {
      isAuthenticated,
    };
  },
};
</script>
<style lang="css"></style>
