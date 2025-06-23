<script setup>
import { onMounted, ref } from "vue";
import { useTicketStore } from "@/stores/ticket";
import { storeToRefs } from "pinia";
import feather from "feather-icons";
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();

const ticketStore = useTicketStore();
const { success, error, loading } = storeToRefs(ticketStore);
const { fetchTicket, editTicket } = ticketStore;

const ticket = ref({});
const form = ref({
  name: "",
  description: "",
  image: null,
  location: "",
  type: "",
  status: "",
});

const previewUrl = ref(null);

// Fetch existing ticket data
const fetchTicketDetail = async () => {
  try {
    const response = await fetchTicket(route.params.code);

    form.value = {
      name: response.name,
      description: response.description,
      location: response.location,
      type: response.type,
      status: response.status,
      image: null,
      _method: "put",
    };

    console.log(response);

    previewUrl.value = response.image_url;
  } catch (error) {
    console.error("Error fetching ticket:", error);
  }
};

// Add new method to handle file changes
const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.value.image = file;
    previewUrl.value = URL.createObjectURL(file);
  }
};

const formErrors = ref({});

const handleSubmit = async () => {
  try {
    formErrors.value = {};

    const formData = new FormData();

    // Pastikan semua field required terisi
    formData.append("name", form.value.name);
    formData.append("description", form.value.description);
    formData.append("location", form.value.location);
    formData.append("type", form.value.type);
    formData.append("status", form.value.status);
    formData.append("_method", "put");
    // Berikan nilai default

    // Jika ada image baru, gunakan itu
    if (form.value.image) {
      formData.append("image", form.value.image);
    }
    // Jika tidak ada image baru tapi ada preview (image lama), kirim URL-nya
    // else if (previewUrl.value) {
    //   const response = await fetch(previewUrl.value);
    //   const blob = await response.blob();
    //   formData.append("image", blob, "existing-image.jpg");
    // }

    await editTicket(route.params.code, formData);

    if (!error.value) {
      router.push({
        name: "app.ticket.detail",
        params: { code: route.params.code },
      });
    }
  } catch (err) {
    if (err.response?.data?.errors) {
      error.value = err.response.data.errors;
    }
    console.error("Form submission error:", err);
  }
};

onMounted(async () => {
  await fetchTicketDetail();
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

    <!-- Create Ticket Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
      <div class="p-6 border-b border-gray-100">
        <h1 class="text-2xl font-bold text-gray-800">Edit Laporan</h1>
        <p class="text-sm text-gray-500 mt-1">Ubah informasi laporan Anda</p>
      </div>

      <form @submit.prevent="handleSubmit" class="p-6 space-y-6">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 mb-2"
            >Judul Laporan</label
          >
          <input
            type="text"
            id="name"
            v-model="form.name"
            placeholder="Contoh: Laptop Hilang di Kelas FIK-302"
            class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
          />
          <div v-if="error?.name" class="flex items-center mt-2">
            <p class="text-xs text-red-500">{{ error.name[0] }}</p>
          </div>
        </div>

        <div>
          <label
            for="description"
            class="block text-sm font-medium text-gray-700 mb-2"
            >Deskripsi Masalah</label
          >
          <textarea
            id="description"
            v-model="form.description"
            rows="6"
            placeholder="Mohon berikan informasi detail mengenai barang yang hilang, mencakup poin-poin berikut:&#10;- Waktu terakhir terlihat&#10;- Langkah-langkah pencarian&#10;- Ciri-ciri khusus barang"
            class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
          ></textarea>
          <div v-if="error?.description" class="flex items-center mt-2">
            <p class="text-xs text-red-500">{{ error.description[0] }}</p>
          </div>
        </div>

        <!-- Input Lokasi -->
        <div>
          <label
            for="location"
            class="block text-sm font-medium text-gray-700 mb-2"
            >Lokasi</label
          >
          <input
            type="text"
            id="location"
            v-model="form.location"
            placeholder="Contoh: Kelas FIK-302"
            class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
          />
          <div v-if="error?.location" class="flex items-center mt-2">
            <p class="text-xs text-red-500">{{ error.location[0] }}</p>
          </div>
        </div>

        <!-- Input Foto -->
        <div class="col-span-full">
          <label
            for="fotobarang"
            class="block text-sm/6 font-medium text-gray-700 mb-2"
          >
            Foto Barang
          </label>
          <div
            class="flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10"
          >
            <div class="flex flex-col justify-center text-center">
              <!-- Add preview image -->
              <div v-if="previewUrl" class="">
                <img
                  :src="previewUrl"
                  alt="Preview"
                  class="max-w-xs rounded-lg"
                />
              </div>
              <div v-else>
                <i
                  data-feather="upload"
                  class="mx-auto size-10 text-gray-300"
                  aria-hidden="true"
                ></i>
              </div>
              <div class="flex justify-center mt-4 text-sm/6 text-gray-600">
                <label
                  for="file-upload"
                  class="relative cursor-pointer rounded-md bg-white font-semibold text-blue-600 focus-within:ring-2 focus-within:ring-blue-600 focus-within:ring-offset-2 focus-within:outline-hidden hover:text-blue-500"
                >
                  <span>Upload foto</span>
                  <input
                    id="file-upload"
                    name="file-upload"
                    type="file"
                    class="sr-only"
                    accept="image/*"
                    @change="handleFileUpload"
                  />
                </label>
                <p class="pl-1">atau drag & drop</p>
              </div>
              <p class="text-xs/5 text-gray-600">PNG / JPG up to 10MB</p>
            </div>
          </div>
        </div>

        <!-- input status postingan -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2"
            >Status Postingan
          </label>

          <div class="grid grid-cols-2 gap-4">
            <label
              class="relative flex cursor-pointer rounded-lg border"
              :class="[
                form.type === 'lost'
                  ? 'border-blue-200 bg-blue-50'
                  : 'border-gray-200',
              ]"
            >
              <input
                type="radio"
                v-model="form.type"
                value="lost"
                class="sr-only"
              />

              <div class="flex w-full items-center justify-between p-4">
                <div class="flex items-center">
                  <div class="text-sm">
                    <p class="font-medium text-gray-900">Barang Hilang</p>
                    <p class="text-gray-500">Lost</p>
                  </div>
                </div>
                <div
                  class="shrink-0 text-blue-600"
                  v-show="form.type === 'lost'"
                >
                  <i data-feather="check-circle" class="w-6 h-6"></i>
                </div>
              </div>
            </label>

            <label
              class="relative flex cursor-pointer rounded-lg border"
              :class="[
                form.type === 'found'
                  ? 'border-yellow-200 bg-yellow-50'
                  : 'border-gray-200',
              ]"
            >
              <input
                type="radio"
                v-model="form.type"
                value="found"
                class="sr-only"
              />
              <div class="flex w-full items-center justify-between p-4">
                <div class="flex items-center">
                  <div class="text-sm">
                    <p class="font-medium text-gray-900">Barang Temuan</p>
                    <p class="text-gray-500">Found</p>
                  </div>
                </div>
                <div
                  class="shrink-0 text-yellow-600"
                  v-show="form.type === 'found'"
                >
                  <i data-feather="check-circle" class="w-6 h-6"></i>
                </div>
              </div>
            </label>
          </div>
          <div v-if="error?.type" class="flex items-center mt-2">
            <p class="text-xs text-red-500">{{ error.type[0] }}</p>
          </div>
        </div>

        <!-- input status barang -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2"
            >Status Barang</label
          >
          <div class="grid grid-cols-2 gap-4">
            <label
              class="relative flex cursor-pointer rounded-lg border"
              :class="[
                form.status === 'pending'
                  ? 'border-yellow-200 bg-yellow-50'
                  : 'border-gray-200',
              ]"
            >
              <input
                type="radio"
                v-model="form.status"
                value="pending"
                class="sr-only"
              />
              <div class="flex w-full items-center justify-between p-4">
                <div class="flex items-center">
                  <div class="text-sm">
                    <p class="font-medium text-gray-900">Dalam Pencarian</p>
                    <p class="text-gray-500">Pending</p>
                  </div>
                </div>
                <div
                  class="shrink-0 text-yellow-600"
                  v-show="form.status === 'pending'"
                >
                  <i data-feather="check-circle" class="w-6 h-6"></i>
                </div>
              </div>
            </label>
            <label
              class="relative flex cursor-pointer rounded-lg border"
              :class="[
                form.status === 'resolved'
                  ? 'border-green-200 bg-green-50'
                  : 'border-gray-200',
              ]"
            >
              <input
                type="radio"
                v-model="form.status"
                value="resolved"
                class="sr-only"
              />
              <div class="flex w-full items-center justify-between p-4">
                <div class="flex items-center">
                  <div class="text-sm">
                    <p class="font-medium text-gray-900">Kasus Terselesaikan</p>
                    <p class="text-gray-500">Resolved</p>
                  </div>
                </div>
                <div
                  class="shrink-0 text-green-600"
                  v-show="form.status === 'resolved'"
                >
                  <i data-feather="check-circle" class="w-6 h-6"></i>
                </div>
              </div>
            </label>
          </div>
          <div v-if="error?.status" class="flex items-center mt-2">
            <p class="text-xs text-red-500">{{ error.status[0] }}</p>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
          <RouterLink
            :to="{ name: 'app.ticket.detail', params: { code: ticket.id } }"
            class="px-6 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50"
          >
            Batal
          </RouterLink>
          <button
            type="submit"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700"
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
