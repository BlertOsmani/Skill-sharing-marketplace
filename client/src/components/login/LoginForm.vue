<script setup>
import { ref } from "vue";
import { useToast } from "primevue/usetoast";

const username = ref("");
const password = ref("");
const rememberMe = ref(false);
const usernameValid = ref(true);
const passwordValid = ref(true);
const toast = useToast();
const serverSidePasswordError = ref("");
const serverSideUsernameError = ref("");

async function handleSubmit() {
  if (validateForm()) {
    try {
      const response = await fetch("http://127.0.0.1:8000/api/user/login", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Access-Control-Allow-Origin": "*",
        },
        mode: "cors",
        body: JSON.stringify({
          username: username.value,
          password: password.value,
          rememberMe: rememberMe.value,
        }),
      });
      if (!response.ok) {
        throw new Error("Something went wrong. Please try again!");
      } else {
        const data = await response.json();
        if (data.errors) {
          updateErrors(data.errors);
        } else {
          toast.add({
            severity: "success",
            summary: "Success",
            detail: "Login successful",
            life: 4000,
          });
          resetErrors();
        }
      }
    } catch (error) {
      console.log("There was a problem with the fetch operation: ", error);
      toast.add({
        severity: "error",
        summary: "Error",
        detail: error.message,
        life: 4000,
      });
    }
  }
}

function updateErrors(errors) {
  serverSidePasswordError.value = errors.password ? errors.password[0] : "";
  serverSideUsernameError.value = errors.username ? errors.username[0] : "";
}

function resetErrors() {
  serverSidePasswordError.value = "";
  serverSideUsernameError.value = "";
}

function validateForm() {
  usernameValid.value = !!username.value;
  passwordValid.value = !!password.value;

  return usernameValid.value && passwordValid.value;
}
</script>

<template>
  <Toast />
  <form
    class="p-fluid grid formgrid sm:w-12 md:w-8 lg:w-6"
    @submit.prevent="handleSubmit"
  >
    <div class="field col-12">
      <label for="username">Username</label>
      <InputText
        id="username"
        v-model="username"
        type="text"
        class="w-full"
        placeholder="Username"
      />
      <small v-if="!usernameValid" class="p-error">Username is required.</small>
      <small class="p-error">{{ serverSideUsernameError }}</small>
    </div>
    <div class="field col-12">
      <label for="password">Password</label>
      <Password
        id="password"
        v-model="password"
        toggleMask
        class="w-full"
        placeholder="Password"
      />
      <small v-if="!passwordValid" class="p-error">Password is required.</small>
      <small class="p-error">{{ serverSidePasswordError }}</small>
    </div>
    <div class="field-checkbox col-12">
      <Checkbox inputId="rememberMe" v-model="rememberMe" binary="true" />
      <label for="rememberMe">Remember Me</label>
    </div>
    <div class="col-12">
      <Button class="w-full" label="Login" @click="handleSubmit" />
    </div>
  </form>
</template>

<style scoped>
.p-fluid .col-12 {
  margin-bottom: 1rem;
}
.p-error {
  color: red;
  font-size: 0.875rem;
}
</style>

<script>
import Toast from "primevue/toast";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Password from "primevue/password";
import Checkbox from "primevue/checkbox";

export default {
  components: {
    Button,
    InputText,
    Password,
    Checkbox,
    Toast,
  },
  setup() {
    return { handleSubmit };
  },
};
</script>
