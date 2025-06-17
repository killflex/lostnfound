<script setup>
import { onMounted, ref, computed } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useTicketStore } from "@/stores/ticket";
import { storeToRefs } from "pinia";
import { capitalize } from "lodash";
import feather from "feather-icons";
import { DateTime } from "luxon";
import { useRoute } from "vue-router";

const route = useRoute();

const authStore = useAuthStore();
const { user } = storeToRefs(authStore);

const isOwner = computed(() => {
  return user.value?.data.id === ticket.value?.user_id;
});

const ticketStore = useTicketStore();
const { success, error, loading } = storeToRefs(ticketStore);
const { fetchTicket, createTicketReply } = ticketStore;

const ticket = ref({});
const form = ref({
  message: "",
});

const fetchTicketDetail = async () => {
  const response = await fetchTicket(route.params.code);

  ticket.value = response;
  form.value.status = response.status;
};

const handleSubmit = async () => {
  try {
    await createTicketReply(route.params.code, form.value);

    await fetchTicketDetail();
  } catch (error) {
    console.error("Error creating ticket reply:", error);
    error.value = { message: ["Gagal mengirim balasan. Silakan coba lagi."] };
  } finally {
    form.value.message = "";
  }
};

onMounted(async () => {
  await fetchTicketDetail();
  feather.replace();
});

console.log(ticket);
</script>

<template>
  <div>
    <!-- Back Button -->
    <div class="mb-6">
      <RouterLink
        :to="{ name: 'app.dashboard' }"
        class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800"
      >
        <i data-feather="arrow-left" class="w-4 h-4 mr-2"></i>
        Kembali ke Daftar Laporan
      </RouterLink>
    </div>

    <!-- Ticket Info -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
      <div class="p-6">
        <div class="flex items-start justify-between">
          <div class="flex gap-4">
            <img
              v-if="ticket.image_url"
              :src="ticket.image_url"
              :alt="ticket.name"
              class="rounded-lg max-h-[300px] aspect-4/3 object-cover"
              @error="handleImageError"
            />
            <div class="flex flex-col justify-between">
              <div class="flex flex-col gap-2">
                <h1 class="text-2xl font-bold text-gray-800">
                  {{ ticket.name }}
                </h1>
                <div class="flex items-center gap-2">
                  <span
                    class="px-3 py-1 text-sm rounded-lg"
                    :class="{
                      'text-red-700 bg-red-100': ticket.type === 'lost',
                      'text-blue-700 bg-blue-100': ticket.type === 'found',
                    }"
                  >
                    {{
                      ticket.type === "lost"
                        ? "Barang Hilang"
                        : ticket.type === "found"
                        ? "Barang Temuan"
                        : ""
                    }}
                  </span>
                  <span
                    class="px-3 py-1 text-sm rounded-lg"
                    :class="{
                      'text-yellow-700 bg-yellow-100':
                        ticket.status === 'pending',
                      'text-green-700 bg-green-100':
                        ticket.status === 'resolved',
                    }"
                  >
                    {{
                      ticket.status === "resolved"
                        ? "Kasus Terselesaikan"
                        : ticket.status === "pending"
                        ? "Dalam Pencarian"
                        : ""
                    }}
                  </span>
                </div>
                <div class="flex flex-col gap-2 mt-2">
                  <p class="flex gap-2 items-center text-sm text-gray-600">
                    <i data-feather="user" class="w-4 h-4"></i>
                    {{ ticket.user?.name }}
                  </p>
                  <p class="flex gap-2 items-center text-sm text-gray-600">
                    <i data-feather="map-pin" class="w-4 h-4"></i>
                    {{ ticket.location }}
                  </p>
                </div>

                <p class="mt-2 text-sm text-gray-600">
                  {{ ticket.description }}
                </p>
              </div>
              <div>
                <span class="text-sm text-gray-500">
                  Dilaporkan pada
                  {{
                    DateTime.fromISO(ticket.created_at).toFormat(
                      "dd MMMM yyyy, HH:mm"
                    )
                  }}
                </span>
                <div class="text-gray-500 text-sm">
                  Diperbarui pada
                  <span class="text-green-500">
                    {{
                      DateTime.fromISO(ticket.updated_at).toFormat(
                        "dd MMMM yyyy, HH:mm"
                      )
                    }}
                  </span>
                </div>
              </div>
            </div>
            <div
              v-if="isOwner"
              class="flex flex-col justify-between max-w-full"
            >
              <RouterLink
                :to="{ name: 'app.ticket.edit', params: { code: ticket.code } }"
                class="flex justify-center items-center px-4 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50"
              >
                <i data-feather="edit" class="w-4 h-4 inline-block mr-2"></i>
                Edit
              </RouterLink>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Discussion Thread -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
      <!-- Thread Header -->
      <div
        class="p-6 border-b border-gray-100"
        v-for="reply in ticket.chats"
        :key="reply.id"
      >
        <div class="flex items-start space-x-4">
          <img
            :src="`https://ui-avatars.com/api/?name=${reply.user.name}&background=0D8ABC&color=fff`"
            alt="User"
            class="w-10 h-10 rounded-full"
          />
          <div class="flex-1">
            <div class="flex items-center justify-between">
              <div>
                <h4 class="text-sm font-medium text-gray-800">
                  {{ reply.user.name }}
                </h4>
                <p class="text-xs text-gray-500">
                  {{
                    DateTime.fromISO(reply.created_at).toFormat(
                      "dd MMMM yyyy, HH:mm"
                    )
                  }}
                </p>
              </div>
            </div>
            <div class="mt-3 text-sm text-gray-800">
              <p>{{ reply.message }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Reply Form -->
      <div class="p-6 border-t border-gray-100">
        <h4 class="text-sm font-medium text-gray-800 mb-4">Tambah Balasan</h4>
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div class="group">
            <textarea
              v-model="form.message"
              class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
              :class="{ 'border-red-500 ring-red-500': error?.message }"
              rows="4"
              placeholder="Tulis balasan Anda di sini..."
              minlength="10"
            ></textarea>
            <p class="mt-1 text-xs text-red-500" v-if="error?.message">
              {{ error?.message?.join(", ") }}
            </p>
          </div>
          <div class="flex items-center justify-end">
            <button
              class="px-6 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700"
            >
              <i data-feather="send" class="w-4 h-4 inline-block mr-2"></i>
              <span v-if="!loading">Kirim Balasan</span>
              <span v-else>Loading...</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
