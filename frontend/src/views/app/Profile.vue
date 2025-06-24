<script setup>
import { onMounted, ref } from "vue";
import { useAuthStore } from "@/stores/auth";
import { storeToRefs } from "pinia";
import feather from "feather-icons";

const authStore = useAuthStore();
const { success, error, loading } = storeToRefs(authStore);
const { fetchCurrentUser, updateCurrentUser } = authStore;

const form = ref({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

// Fetch existing ticket data
const fetchCurrentUserDetail = async () => {
  try {
    const response = await fetchCurrentUser();

    form.value = {
      name: response.name || "",
      email: response.email || "",
    };
  } catch (error) {
    console.error("Error loading user data:", error);
  }
};

const handleSubmit = async () => {
  try {
    const payload = {};
    const currentUser = await fetchCurrentUser();

    // Only include name if it changed
    if (form.value.name !== currentUser.name) {
      payload.name = form.value.name;
    }

    // Only include email if it changed
    if (form.value.email !== currentUser.email) {
      payload.email = form.value.email;
    }

    // Only include password if provided
    if (form.value.password) {
      if (form.value.password !== form.value.password_confirmation) {
        error.value = {
          general: ["Password confirmation doesn't match"],
        };
        return;
      }
      payload.password = form.value.password;
      payload.password_confirmation = form.value.password_confirmation;
    }

    await updateCurrentUser(payload);

    if (!error.value) {
      form.value.password = "";
      form.value.password_confirmation = "";
    }
    console.log("Form data:", form.value);
  } catch (err) {
    if (err.response?.data?.errors) {
      error.value = err.response.data.errors;
    }
    console.error("Profile update error:", err);
  }
};

onMounted(async () => {
  await fetchCurrentUserDetail();
  feather.replace();
});
</script>

<template>
  <div>
    <div
      v-if="error?.general"
      class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg"
    >
      <p class="text-sm text-red-600">{{ error.general[0] }}</p>
    </div>

    <div class="mb-6">
      <RouterLink
        :to="{ name: 'app.dashboard' }"
        class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800"
      >
        <i data-feather="arrow-left" class="w-4 h-4 mr-2"></i>
        Kembali ke Daftar Laporan
      </RouterLink>
    </div>

    <div
      class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative flex items-center justify-between"
      role="alert"
      v-if="success"
    >
      <div class="flex items-center">
        <i data-feather="check-circle" class="w-5 h-5 mr-2"></i>
        <span>{{ success }}</span>
      </div>

      <button class="flex items-center justify-center" @click="success = null">
        <i data-feather="x" class="w-4 h-4 text-green-600"></i>
      </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
      <div class="p-6 border-b border-gray-100">
        <h1 class="text-2xl font-bold text-gray-800">Profile</h1>
        <p class="text-sm text-gray-500 mt-1">Ubah informasi Anda</p>
      </div>

      <form @submit.prevent="handleSubmit" class="p-6 space-y-6">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 mb-2"
            >Nama
          </label>
          <input
            type="text"
            id="name"
            v-model="form.name"
            class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
          />
          <div v-if="error?.name" class="flex items-center mt-2">
            <p class="text-xs text-red-500">{{ error.name[0] }}</p>
          </div>
        </div>
        <div>
          <label
            for="email"
            class="block text-sm font-medium text-gray-700 mb-2"
            >Email
          </label>
          <input
            type="email"
            id="email"
            v-model="form.email"
            class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
          />
          <div v-if="error?.email" class="flex items-center mt-2">
            <p class="text-xs text-red-500">{{ error.email[0] }}</p>
          </div>
        </div>
        <div>
          <label
            for="password"
            class="block text-sm font-medium text-gray-700 mb-2"
            >Password
          </label>
          <input
            type="password"
            id="password"
            v-model="form.password"
            class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
          />
          <div v-if="error?.password" class="flex items-center mt-2">
            <p class="text-xs text-red-500">{{ error.password[0] }}</p>
          </div>
        </div>
        <div>
          <label
            for="password_confirmation"
            class="block text-sm font-medium text-gray-700 mb-2"
            >Konfirmasi Password
          </label>
          <input
            type="password"
            id="password_confirmation"
            v-model="form.password_confirmation"
            class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
          />
          <div
            v-if="error?.password_confirmation"
            class="flex items-center mt-2"
          >
            <p class="text-xs text-red-500">
              {{ error.password_confirmation[0] }}
            </p>
          </div>
        </div>

        <div class="flex justify-end space-x-4">
          <RouterLink
            :to="{ name: 'app.dashboard' }"
            class="px-6 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50"
          >
            Batal
          </RouterLink>
          <button
            type="submit"
            class="cursor-pointer px-6 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700"
          >
            <i data-feather="send" class="w-4 h-4 inline-block mr-2"></i>
            <span v-if="!loading">Simpan Perubahan</span>
            <span v-else>Loading...</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
