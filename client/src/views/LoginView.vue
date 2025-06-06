<template>
  <div>
    <h2>Login</h2>
    <form @submit.prevent="handleLogin">
      <div>
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="email" required class="form-control">
      </div>
      <div>
        <label for="password">Password:</label>
        <input type="password" id="password" v-model="password" required class="form-control">
      </div>
      <button type="submit" class="button">Login</button>
    </form>
    <div v-if="message">{{ message }}</div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const email = ref('');
const password = ref('');
const message = ref('');

async function handleLogin() {

  const loginData = {
    email: email.value,
    password: password.value,
  };

  console.log('Sending login data:', loginData);
  message.value = 'Attempting to login...';

  try {

    const response = await fetch('YOUR_API_ENDPOINT/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(loginData),
    });

    if (response.ok) {
      const responseData = await response.json();
      console.log('Login successful:', responseData);
      message.value = 'Login successful! ' + (responseData.message || '');

      email.value = '';
      password.value = '';
      
    } else {

      const errorData = await response.json();
      console.error('Login failed:', response.status, errorData);
      message.value = `Login failed: ${errorData.message || response.statusText}`;
    }
  } catch (error) {

    console.error('An error occurred during login:', error);
    message.value = 'An error occurred. Please try again.';
  }
}
</script>