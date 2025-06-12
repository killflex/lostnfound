<template>
  <div class="register-container">
    <div class="register-card">
      <h2 class="register-title">Create an Account</h2>
      <p class="register-subtitle">Enter your details to register.</p>

      <form @submit.prevent="handleRegister">
        <div class="input-group">
          <label for="name">Name</label>
          <input type="text" id="name" v-model="form.name" required autocomplete="name" />
          <div v-if="errors.name" class="error-message">{{ errors.name[0] }}</div>
        </div>

        <div class="input-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" v-model="form.email" required autocomplete="email" />
          <div v-if="errors.email" class="error-message">{{ errors.email[0] }}</div>
        </div>

        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" v-model="form.password" required autocomplete="new-password" />
          <div v-if="errors.password" class="error-message">{{ errors.password[0] }}</div>
        </div>

        <div class="input-group">
          <label for="password_confirmation">Confirm Password</label>
          <input type="password" id="password_confirmation" v-model="form.password_confirmation" required autocomplete="new-password" />
        </div>

        <div v-if="message" :class="messageType === 'success' ? 'success-message' : 'error-message-general'">
          {{ message }}
        </div>

        <button type="submit" :disabled="isLoading" class="submit-button">
          {{ isLoading ? 'Registering...' : 'Register' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const errors = ref({});
const message = ref('');
const messageType = ref('');
const isLoading = ref(false);

const handleRegister = async () => {
  errors.value = {};
  message.value = '';
  isLoading.value = true;

  try {
    const response = await fetch('http://127.0.0.1:8000/api/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(form.value),
    });

    const data = await response.json();

    if (!response.ok) {
      if (response.status === 422) {
        errors.value = data.errors;
        message.value = 'Please correct the errors above.';
        messageType.value = 'error';
      } else {
        throw new Error(data.message || 'An error occurred during registration.');
      }
    } else {
      message.value = data.message;
      messageType.value = 'success';
      
      form.value.name = '';
      form.value.email = '';
      form.value.password = '';
      form.value.password_confirmation = '';
    }
  } catch (error) {
    console.error('Registration failed:', error);
    message.value = error.message || 'A network error occurred. Please try again.';
    messageType.value = 'error';
  } finally {
    isLoading.value = false;
  }
};
</script>

<style scoped>
.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #f0f2f5;
  font-family: sans-serif;
}

.register-card {
  background: white;
  padding: 2.5rem;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
  box-sizing: border-box;
}

.register-title {
  font-size: 1.75rem;
  font-weight: bold;
  text-align: center;
  margin-bottom: 0.5rem;
  color: #333;
}

.register-subtitle {
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
  border-color: #007bff;
}

.error-message {
  color: #dc3545;
  font-size: 0.875em;
  margin-top: 0.25rem;
}

.error-message-general {
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
  background-color: #007bff;
  color: white;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s, opacity 0.3s;
}

.submit-button:hover {
  background-color: #0056b3;
}

.submit-button:disabled {
  background-color: #6c757d;
  opacity: 0.7;
  cursor: not-allowed;
}
</style>
