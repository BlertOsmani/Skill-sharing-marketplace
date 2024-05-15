<script setup>
import { ref } from "vue";
import { useToast } from "primevue/usetoast";

const username = ref("");
const password = ref("");
const rememberMe = ref(false);
var usernameValid = ref(true);
var passwordValid = ref(true);
const toast = useToast();
var serverSidePasswordError = ref("");
var serverSideUsernameError = ref("");

async function handleSubmit() {
  if (validateForm()) {
    try {
      const response = await fetch(
        "http://127.0.0.1:8000/api/auth/user/login",
        {
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
        }
      );
      if (!response.ok) {
        throw new Error("Something went wrong. Please try again!");
      } else {
        const data = await response.json();
        console.log(data);
        if (data.errors) {
          updateErrors(data.errors);
        } else {
          toast.add({
            severity: "error",
            summary: "Error",
            detail: data.message,
            life: 4000,
          });
          resetErrors();
        }
      }
    } catch (error) {
      console.log("There was a problem with the fetch operation: ", error);
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
  <form class="login-form" @submit.prevent="handleSubmit">
    <div class="login-form-fields-container">
      <InputText
        v-model="username"
        type="text"
        placeholder="Username"
        class="login-fields"
      />
      <span v-if="!usernameValid" class="error-message"
        >Username is required.</span
      >
      <span class="server-side-error">{{ serverSideUsernameError }}</span>
    </div>
    <div class="login-form-fields-container">
      <Password
        v-model="password"
        toggleMask
        placeholder="Password"
        class="login-fields"
      />
      <span v-if="!passwordValid" class="error-message"
        >Password is required.</span
      >
      <span class="server-side-error">{{ serverSidePasswordError }}</span>
    </div>
    <div class="login-form-remember-me">
      <Checkbox
        v-model="rememberMe"
        binary="true"
        class="remember-me-checkbox"
      />
      <label for="rememberMe" class="remember-me-label">Remember Me</label>
    </div>
    <div class="login-form-submit-button-container">
      <Button class="login-submit-button" label="Login" @click="handleSubmit" />
    </div>
  </form>
</template>

<style scoped>
.login-form {
  max-width: 400px;
  margin: 0 auto;
}

.login-form-fields-container {
  margin-bottom: 1rem;
  margin-right: 0;
}
.login-fields {
  width: 100%;
}

.login-form-remember-me {
  display: flex;
  align-items: center;
  margin-bottom: 1rem;
}

.login-form-submit-button-container {
  text-align: center;
}

.error-message {
  color: red;
}

.server-side-error {
  color: red;
  font-size: 0.8rem;
  margin-top: 0.2rem;
}
.remember-me-checkbox {
  margin-right: 1rem;
}
.remember-me-label {
  color: white;
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
