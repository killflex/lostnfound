<template>

  <form @submit.prevent="submitRegister">
    <input v-model="registerForm.name" type="text" placeholder="Nama" class="form-control">
    <input v-model="registerForm.email" type="text" placeholder="E-Mail" class="form-control">
    <input v-model="registerForm.password" type="password" placeholder="Password" class="form-control">
    <button type="submit" class="button">Register</button>
    <RouterLink :to="{ name: 'login' }" class="border-b">Login</RouterLink>
  </form>

</template>

<script setup>

  import { ref } from 'vue';

  const registerForm = ref({
    name: "",
    email: "",
    password: ""
  });

  const submitRegister = () => {
    console.log(registerForm.value.name, registerForm.value.email, registerForm.value.password);
  }

</script>