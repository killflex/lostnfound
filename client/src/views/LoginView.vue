<template>
  <div class="login-container">
    <div class="login-card">
      <h2 class="login-title">Welcome Back!</h2>
      <p class="login-subtitle">Please sign in to your account.</p>

      <form @submit.prevent="handleLogin">
        <div class="input-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" v-model="form.email" required autocomplete="email" />
        </div>

        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" v-model="form.password" required autocomplete="current-password" />
        </div>

        <div v-if="message" :class="messageType === 'success' ? 'success-message' : 'error-message'">
          {{ message }}
        </div>

        <button type="submit" :disabled="isLoading" class="submit-button">
          {{ isLoading ? 'Logging In...' : 'Login' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const form = ref({
  email: '',
  password: '',
});

const message = ref('');
const messageType = ref('');
const isLoading = ref(false);

const handleLogin = async () => {
  message.value = '';
  isLoading.value = true;

  try {
    const response = await fetch('http://127.0.0.1:8000/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(form.value),
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.error || 'An unknown error occurred.');
    }

    message.value = data.message;
    messageType.value = 'success';
    
    console.log('Token:', data.token);
    console.log('User Role:', data.role);
    
    form.value.password = '';

  } catch (error) {
    console.error('Login failed:', error);
    message.value = error.message;
    messageType.value = 'error';
  } finally {
    isLoading.value = false;
  }
};
</script>

<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #f0f2f5;
  font-family: sans-serif;
}

.login-card {
  background: white;
  padding: 2.5rem;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
  box-sizing: border-box;
}

.login-title {
  font-size: 1.75rem;
  font-weight: bold;
  text-align: center;
  margin-bottom: 0.5rem;
  color: #333;
}

.login-subtitle {
  text-align: center;
  color: #666;
  margin-bottom: 2rem;
}

.input-group {
  margin-bottom: 1.25rem;
}

.input-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #333;
}

.input-group input {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-sizing: border-box;
  transition: border-color 0.3s;
}

.input-group input:focus {
  outline: none;
  border-color: #28a745;
}

.error-message {
  color: #dc3545;
  background-color: #f8d7da;
  border: 1px solid #f5c6cb;
  padding: 0.75rem 1rem;
  border-radius: 8px;
  margin-bottom: 1.25rem;
  text-align: center;
}

.success-message {
  color: #155724;
  background-color: #d4edda;
  border: 1px solid #c3e6cb;
  padding: 0.75rem 1rem;
  border-radius: 8px;
  margin-bottom: 1.25rem;
  text-align: center;
}

.submit-button {
  width: 100%;
  padding: 0.85rem;
  border: none;
  border-radius: 8px;
  background-color: #28a745;
  color: white;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s, opacity 0.3s;
}

.submit-button:hover {
  background-color: #218838;
}

.submit-button:disabled {
  background-color: #6c757d;
  opacity: 0.7;
  cursor: not-allowed;
}
</style>