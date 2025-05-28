<template>

  <form @submit.prevent="submitLogin">
    <input v-model="loginForm.email" type="text" placeholder="E-Mail" class="form-control">
    <input v-model="loginForm.password" type="password" placeholder="Password" class="form-control">
    <button type="submit" class="button">Login</button>
    <RouterLink :to="{ name: 'register' }" class="border-b">Register</RouterLink>
  </form>

</template>

<script setup>

  import { ref } from 'vue';

  const loginForm = ref({
    email: "",
    password: ""
  });

  const submitLogin = () => {
    console.log(loginForm.value.email, loginForm.value.password);
  }

</script>