<template>
  <div class="container">
    <div class="form-frame mb-5">
      <div v-if="emailSent" class="success-message border-primary-500">
        <p class="text-primary">Check your email for reset link.</p>
      </div>
      <div>
        <form @submit.prevent="submitEmail" class="form-content">
          <h3 class="form-title">Forgot Password</h3>
          <p class="form-paragraph">Enter the email address associated with your account and we'll send you a link to reset your password</p>
          <div class="form-group">
            <InputText v-model="email" type="email" placeholder="Email address" class="custom-input"/>
            <span v-if="!isEmailValid && email !== ''" class="error-message">Please enter a valid email.</span>
          </div>
          <Button label="Send Reset Link" type="submit" class="custom-button"></Button>
        </form>
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
      emailSent: false
    }
  },
  methods: {
    isEmailValid() {
      const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return regex.test(this.email);
    },
    async submitEmail() {
      if (!this.isEmailValid()) {
        return;
    }

      try {
        const response = await axios.post('http://localhost:8000/api/forgot', { email: this.email });
        if(response.data.message){
          this.emailSent = true;
        }
      } catch (error) {
        if (error.response) {
          alert(error.response.data.error || 'An error occurred. Please try again.');
        } else {
          alert('An error occurred. Please try again.');
        }
      }
    }
  }
};
</script>

<style>

.form-frame {
  position:absolute;
  transform: translate(-50%,-50%);
  top:50%;
  left:50%;
  width: 410px;
  padding: 20px;
  border-radius: 8px;
  background-color: var(--surface-card);
}

.form-content {
  margin: 0;
}

.form-title {
  margin-bottom: 25px;
  text-align: center;
  font-size: 22px;
}

.form-paragraph {
  font-size: 14px;
  margin-bottom: 15px;
  width: 100%;
}

.form-group {
  margin-bottom: 0;
  margin-top: 30px;
}

.custom-input {
  width: 100%;
  padding: 12px;
}

.custom-button {
  width: 100%;
  margin-top: 20px;
  padding: 12px;
}

.error-message {
  color: red;
}

.success-message {
  text-align: center;
  background-color: var(--highlight-bg);
  border-radius: var(--border-radius); 
  padding: 20px; /* Adjust padding as needed */ /* Limit the maximum width of the success message */
}
</style>