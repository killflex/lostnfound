<template>

  <div class="min-h-screen flex items-center justify-center bg-gray-100 p-4 font-inter">
    <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-md">
      <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Create Account</h2>
      <form @submit.prevent="handleSubmit">
        <div class="mb-6">
          <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
          <input type="text" id="name" v-model="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 focus:border-blue-500 outline-none transition duration-150 ease-in-out" placeholder="John Doe" required />
        </div>
        <div class="mb-6">
          <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
          <input type="email" id="email" v-model="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 focus:border-blue-500 outline-none transition duration-150 ease-in-out" placeholder="you@example.com" required />
        </div>
        <div class="mb-6">
          <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
          <input type="password" id="password" v-model="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 focus:border-blue-500 outline-none transition duration-150 ease-in-out" placeholder="••••••••" required />
        </div>
        <div class="mb-8">
          <label for="passwordConfirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
          <input type="password" id="passwordConfirmation" v-model="passwordConfirmation" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 focus:border-blue-500 outline-none transition duration-150 ease-in-out" placeholder="••••••••" required />
        </div>
        <div v-if="passwordError" class="mb-4 p-3 bg-red-100 border border-red-300 text-red-700 rounded-lg text-sm">{{ passwordError }}</div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 ease-in-out focus:outline-none focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 shadow-md hover:shadow-lg">Register</button>
      </form>
      <div v-if="submittedData" class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200 shadow-sm">
        <h3 class="text-md font-semibold text-gray-700 mb-2">Submitted Data (JSON):</h3>
        <pre class="text-sm text-gray-600 bg-white p-3 rounded-md overflow-x-auto">{{ submittedData }}</pre>
      </div>
      <p class="text-center text-sm text-gray-600 mt-8">
        Already have an account?
        <a href="#" class="font-semibold text-blue-600 hover:text-blue-800 hover:underline">Login</a>
      </p>
    </div>
  </div>
	
</template>

<script setup>

	import { ref } from 'vue';

	const name = ref('');
	const email = ref('');
	const password = ref('');
	const passwordConfirmation = ref('');
	const submittedData = ref(null);
	const passwordError = ref('');

	const handleSubmit = () => {
	  passwordError.value = '';
	  submittedData.value = null;

	  if (password.value !== passwordConfirmation.value) {
	    passwordError.value = 'Passwords do not match. Please try again.';
	    return;
	  }

	  const registrationDetails = { name: name.value, email: email.value, password: password.value, password_confirmation: passwordConfirmation.value };
	  const jsonData = JSON.stringify(registrationDetails, null, 2);

	  console.log('Registration Data (JSON):', jsonData);
	  submittedData.value = jsonData;
	};

</script>

<style scoped>

	.font-inter {
	  font-family: 'Inter', sans-serif;
	}

	input[type="text"], input[type="email"], input[type="password"] {
	  transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
	}

</style>