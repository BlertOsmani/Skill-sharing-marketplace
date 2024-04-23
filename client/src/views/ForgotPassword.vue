<template>
    <div class="container">
      <div class="form-frame">
        <form @submit.prevent="handleSubmit" class="form-content">
          <h3 class="form-title">Forgot Password</h3>
          <p class="form-paragraph">Enter the email address associated with your account and we'll send you a link to reset your password</p>
          <div class="form-group">
            <InputText v-model="email" type="email" placeholder="Email address" class="custom-input"/>
            <span v-if="!isEmailValid && email !== ''" class="error-message">Please enter a valid email.</span>
            <span class="server-side-error">{{serverSideEmailError}}</span>
          </div>
          <Button label="Send" type="submit" class="custom-button"></Button>
        </form>
        <div class="bottom-text">
          <span style="margin:1.2rem 0 2rem 0;">Already have an account? <Button style="padding:0;" label="Sign in" link></Button></span>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import Button from 'primevue/button';
  import InputText from 'primevue/inputtext';
  import axios from 'axios';
  
  export default {
    components: {
      Button,
      InputText
    },
    name: 'Forgot',
    data() {
      return {
        email: '',
        serverSideEmailError: ''
      }
    },
    computed: {
      isEmailValid() {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(this.email);
      }
    },
    methods: {
      async handleSubmit() {
        const response = await axios.post('forgot', {
            email: this.email
        });
        console.log(response);
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
    margin-top: 30px;
  }
  
  .custom-input {
    width: 100%; /* Set input text width to 100% */
  }
  
  .custom-button {
    width: 100%; /* Set button width to 100% */
    margin-top: 8px;
  }
  
  .bottom-text {
    position: absolute;
    bottom: 30px; /* Adjust the distance from the bottom */
    left: 0;
    width: 100%;
    text-align: center;
    margin-bottom: 5px;
  }
  
  .bottom-text span {
    margin-right: 5px;
  }
  
  .error-message {
    color: red;
  }
  
  /* Styles for InputText and Button components can be added if needed */
  </style>
  