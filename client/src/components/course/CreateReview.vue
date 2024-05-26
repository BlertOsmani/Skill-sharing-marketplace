<template>
    <div class="comment-form">
      <form @submit.prevent="submitForm">
        <div>
          <label for="name">Name:</label>
          <input type="text" id="name" v-model="form.name" required>
        </div>
        <div>
          <label for="email">Email:</label>
          <input type="email" id="email" v-model="form.email" required>
        </div>
        <div>
          <label for="comment">Comment:</label>
          <textarea id="comment" v-model="form.comment" required></textarea>
        </div>
        <div>
          <label for="rating">Rating:</label>
          <select id="rating" v-model="form.rating" required>
            <option v-for="star in 5" :key="star" :value="star">{{ star }} star{{ star > 1 ? 's' : '' }}</option>
          </select>
        </div>
        <button type="submit">Submit</button>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        form: {
          name: '',
          email: '',
          comment: '',
          rating: null
        }
      };
    },
    methods: {
      submitForm() {
        console.log('Form submitted with:', this.form);
        // Add your form submission logic here, such as an API call.
      }
    }
  };
  </script>
  
  <style scoped>
  .comment-form {
    max-width: 600px;
    margin: 0 auto;
  }
  
  .comment-form div {
    margin-bottom: 15px;
  }
  
  .comment-form label {
    display: block;
    margin-bottom: 5px;
  }
  
  .comment-form input,
  .comment-form textarea,
  .comment-form select {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
  }
  
  .comment-form button {
    padding: 10px 20px;
    background-color: #42b983;
    color: white;
    border: none;
    cursor: pointer;
  }
  
  .comment-form button:hover {
    background-color: #38a171;
  }
  </style>
  