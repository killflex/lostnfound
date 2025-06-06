<template>
  <div>
    <h2>Register</h2>
    <form @submit.prevent="handleRegister">
      <div>
        <label for="name">Name:</label>
        <input type="text" id="name" v-model="name" required class="form-control">
      </div>
      <div>
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="email" required class="form-control">
      </div>
      <div>
        <label for="password">Password:</label>
        <input type="password" id="password" v-model="password" required class="form-control">
      </div>
      <div>
        <label for="password_confirmation">Confirm Password:</label>
        <input type="password" id="password_confirmation" v-model="passwordConfirmation" required class="form-control">
      </div>
      <button type="submit" class="button">Register</button>
    </form>
    <div v-if="message">{{ message }}</div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const name = ref('');
const email = ref('');
const password = ref('');
const passwordConfirmation = ref('');
const message = ref('');

async function handleRegister() {

  if (password.value !== passwordConfirmation.value) {
    message.value = 'Passwords do not match.';
    console.error('Passwords do not match.');
    return;
  }

  const userData = {
    name: name.value,
    email: email.value,
    password: password.value,
    password_confirmation: passwordConfirmation.value,
  };

  console.log('Sending registration data:', userData);
  message.value = 'Attempting to register...';

  try {

    const response = await fetch('YOUR_API_ENDPOINT/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(userData),
    });

    if (response.ok) {
      const responseData = await response.json();
      console.log('Registration successful:', responseData);
      message.value = 'Registration successful! ' + (responseData.message || '');

      name.value = '';
      email.value = '';
      password.value = '';
      passwordConfirmation.value = '';
    } else {
      const errorData = await response.json();
      console.error('Registration failed:', response.status, errorData);
      message.value = `Registration failed: ${errorData.message || response.statusText}`;
    }
    
  } catch (error) {
    console.error('An error occurred during registration:', error);
    message.value = 'An error occurred. Please try again.';
  }
}
</script>