<template>
    <div class="container">
      <div class="form-frame">
        <form @submit.prevent="handleSubmit" class="form-content">
          <h3 class="form-title">Reset Password</h3>
          <p class="form-paragraph">Create a new password</p>
          <div class="form-group">
            <Password v-model="password" @blur="passwordTouched = true" toggleMask placeholder="Password" class="custom-input"/>
            <span v-if="!password && passwordTouched" class="error-message">Password is required.</span>
            <span v-if="password && confirmPassword && password !== confirmPassword" class="error-message">Passwords do not match.</span>
            <span class="server-side-error">{{serverSidePasswordError}}</span>
          </div>
          <div class="form-group" style="margin-top: 10px;">
            <Password v-model="confirmPassword" toggleMask placeholder="Confirm password" class="custom-input"/>
            <span v-if="!confirmPassword && confirmPasswordTouched" class="error-message">Please confirm your password.</span>
            <span v-if="password && confirmPassword && password !== confirmPassword" class="error-message">Passwords do not match.</span>
            <span class="server-side-error">{{serverSideConfirmPasswordError}}</span>
          </div>
          <Button label="Continue" @click="handleSubmit" class="custom-button" style="margin-top: 20px;"></Button>
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
    name: 'Forgot',
    data() {
      return {
        email: '',
        password: '',
        confirmPassword: '',
        passwordTouched: false,
        confirmPasswordTouched: false
      }
    },
    methods: {
      async handleSubmit() {
        try {
          const response = await axios.post('forgot', {
            email: this.email
          });
          console.log(response.data); // Assuming response contains some data
        } catch (error) {
          console.error('Error:', error);
        }
      }
    }
  }
  </script>
  
  <style>
  .container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }
  
  .form-frame {
    width: 410px; /* Increased width */
    height: 350px; /* Increased height */
    padding: 20px;
    border-radius: 8px; /* Increased border radius */
    position: relative; /* Position relative for positioning the bottom text */
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
  
  .form-paragraph {
    font-size: 14px; /* Decreased font size */
    margin-bottom: 15px; /* Increased margin-bottom */
    width: 100%; /* Increased width */
  }
  
  .form-group {
    margin-bottom: 0;
  }
  
  .form-group input {
    width: 100%;
  }
  
  .custom-button {
    width: 100%; /* Set button width to 100% */
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
  