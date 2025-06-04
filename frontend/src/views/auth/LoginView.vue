<template>

  <div class="min-h-screen flex items-center justify-center bg-gray-100 p-4 font-inter">
    <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-md">
      <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Welcome Back!</h2>
      <form @submit.prevent="handleSubmit">
        <div class="mb-6">
          <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
          <input type="email" id="email" v-model="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 focus:border-blue-500 outline-none transition duration-150 ease-in-out" placeholder="you@example.com" required />
        </div>
        <div class="mb-8">
          <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
          <input type="password" id="password" v-model="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 focus:border-blue-500 outline-none transition duration-150 ease-in-out" placeholder="••••••••" required />
          <div class="text-right mt-2">
            <a href="#" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">Forgot Password?</a>
          </div>
        </div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 ease-in-out focus:outline-none focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 shadow-md hover:shadow-lg">Login</button>
      </form>
      <p class="text-center text-sm text-gray-600 mt-8">
        Don't have an account?
        <RouterLink :to="{ name: 'register' }" class="font-semibold text-blue-600 hover:text-blue-800 hover:underline">Sign Up</RouterLink>
      </p>
    </div>
  </div>

</template>

<script setup>

  import { ref } from 'vue';

  const email = ref('');
  const password = ref('');

  const handleSubmit = () => {
    const loginCredentials = {
      email: email.value,
      password: password.value,
    };
    const jsonData = JSON.stringify(loginCredentials, null, 2);
    console.log('Login Data (JSON):', jsonData);
  };

</script>

<style scoped>

  .font-inter {
    font-family: 'Inter', sans-serif;
  }

  input[type="email"], input[type="password"] {
    transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
  }
  
</style>
