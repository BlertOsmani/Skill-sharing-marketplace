<template>
  <div class="container">
    <div class="form-framee">
      <form @submit.prevent="resetPassword" class="form-content">
        <h3 class="text-center mb-1">Change your password</h3>
        <div class="w-12 flex justify-content-center p-0 m-0 mb-5">
          <p class="text-xs text-400 text-center w-10 m-1">Enter a new password below to change your password</p>
        </div>
        <div class="form-group-r my-1">
          <Password v-model="password" @blur="passwordTouched = true" toggleMask placeholder="New Password" class="w-12"/>
          <span v-if="!password && passwordTouched" class="error-message my-1">Password is required.</span>
          <span v-if="password && confirmPassword && password !== confirmPassword" class="error-message">Passwords do not match.</span>
          <span class="server-side-error">{{serverSidePasswordError}}</span>
        </div>
        <div class="form-group-r my-3">
          <Password v-model="confirmPassword" @blur="confirmPasswordTouched = true" toggleMask placeholder="Confirm Password" class="w-12"/>
          <span v-if="!confirmPassword && confirmPasswordTouched" class="error-message mt-3">Please confirm your password.</span>
          <span v-if="password && confirmPassword && password !== confirmPassword" class="error-message">Passwords do not match.</span>
          <span class="server-side-error">{{serverSideConfirmPasswordError}}</span>
        </div>
        <Button label="Reset Password" type="submit" class="custom-button" style="margin-top: 20px;"></Button>
        <span v-if="serverSideTokenError" class="error-message mt-3">{{serverSideTokenError}}</span>
      </form>
    </div>
  </div>
</template>

<script>
import Password from 'primevue/password';
import Button from 'primevue/button';
import axios from 'axios';

export default {
  components: {
    Password,
    Button,
  },
  name: 'ResetPassword',
  data() {
    return {
      password: '',
      confirmPassword: '',
      passwordTouched: false,
      confirmPasswordTouched: false,
      token: '',
      email: '',
      serverSidePasswordError: '',
      serverSideConfirmPasswordError: '',
      serverSideTokenError: ''
    }
  },
  created() {
    this.token = this.$route.query.token;
    this.email = this.$route.query.email; // Retrieve email from the query parameters if provided
  },
  methods: {
    async resetPassword() {
      this.serverSidePasswordError = '';
      this.serverSideConfirmPasswordError = '';
      this.serverSideTokenError = '';

      if (this.password !== this.confirmPassword) {
        this.serverSideConfirmPasswordError = "Passwords do not match.";
        return;
      }

      try {
        const response = await axios.post('http://localhost:8000/api/password/reset', {
          email: this.email, // Include email in the request payload
          password: this.password,
          password_confirmation: this.confirmPassword,
          token: this.token
        });
        alert(response.data.message);
        if (response.status === 200) {
          this.$router.push('/login');
        }
      } catch (error) {
        if (error.response) {
          const data = error.response.data;
          if (data.errors) {
            const errors = data.errors;
            if (errors.password) {
              this.serverSidePasswordError = errors.password.join(', ');
            }
            if (errors.password_confirmation) {
              this.serverSideConfirmPasswordError = errors.password_confirmation.join(', ');
            }
          } else if (data.error) {
            this.serverSideTokenError = data.error;
          }
        } else {
          console.error('Error:', error);
        }
      }
    }
  }
}
</script>

<style lang="css">
.container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.form-framee {
  position: absolute;
  top: 50%;
  transform: translate(-50%, -50%);
  left: 50%;
  width: 410px; /* Increased width */
  padding: 20px;
  border-radius: 8px; /* Increased border radius */
  background-color: var(--surface-card);
}

.form-content {
  margin: 0; /* Remove default margin */
}

.form-title {
  margin-bottom: 25px; /* Increased margin-bottom */
  text-align: center;
  font-size: 22px; /* Increased font size */
}

.form-group-r {
  margin-bottom: 0;
}

.form-group-r input {
  width: 100%;
  padding: 12px;
}

.custom-button {
  width: 100%; /* Set button width to 100% */
}

.error-message {
  color: red;
  font-size: 12px;
  margin-top: 5px;
}

.server-side-error {
  color: red;
  font-size: 12px;
  margin-top: 5px;
}

.bottom-text {
  position: absolute;
  bottom: 30px; /* Adjust the distance from the bottom */
  left: 0;
  width: 100%;
  text-align: center;
}

.bottom-text span {
  margin-right: 5px;
}

/* Styles for InputText and Button components can be added if needed */
</style>
