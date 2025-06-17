<script setup>
import { onMounted, ref, watch } from "vue";
import { useTicketStore } from "@/stores/ticket";
import { storeToRefs } from "pinia";
import { debounce } from "lodash";
import feather from "feather-icons";
import { DateTime } from "luxon";
import { capitalize } from "lodash";

const ticketStore = useTicketStore();
const { tickets, success, loading } = storeToRefs(ticketStore);
const { fetchTickets } = ticketStore;

const filters = ref({
  search: "",
  type: "",
  status: "",
  laporan: "",
});

watch(
  filters,
  debounce(async () => {
    await fetchTickets({
      search: filters.value.search,
      type: filters.value.type,
      status: filters.value.status,
      laporan: filters.value.laporan,
    });
  }, 300),
  { deep: true }
);

onMounted(async () => {
  await fetchTickets();
  feather.replace();
});
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Laporan Barang</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola dan pantau laporan</p>
      </div>
      <RouterLink
        :to="{ name: 'app.ticket.create' }"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
      >
        <i data-feather="plus" class="w-4 h-4 mr-2"></i>
        Buat Laporan
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

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
      <div class="p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="relative">
            <input
              type="text"
              placeholder="Cari laporan..."
              v-model="filters.search"
              class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
            />
            <i
              data-feather="search"
              class="w-4 h-4 text-gray-400 absolute left-3 top-2.5"
            ></i>
          </div>
          <select
            v-model="filters.type"
            class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
          >
            <option value="">Status Postingan</option>
            <option value="lost">Barang Hilang</option>
            <option value="found">Barang Temuan</option>
          </select>
          <select
            v-model="filters.status"
            class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
          >
            <option value="">Status Barang</option>
            <option value="pending">Dalam Pencarian</option>
            <option value="resolved">Kasus Terselesaikan</option>
          </select>
          <select
            v-model="filters.laporan"
            class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
          >
            <option value="">Semua Laporan</option>
            <option value="user">Laporan Saya</option>
          </select>
        </div>
      </div>
    </div>

    <div
      class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 my-4 p-6 bg-white rounded-xl shadow-sm border border-gray-100"
    >
      <div
        class="max-w-[372px] w-full bg-white rounded-xl shadow-lg overflow-hidden"
        v-for="ticket in tickets"
        :key="ticket.id"
      >
        <div class="relative">
          <img
            :src="ticket.image_url"
            :alt="ticket.name"
            class="w-full h-52 object-cover"
          />
        </div>

        <div class="p-5 space-y-4">
          <div class="flex flex-col space-y-2">
            <h3 class="text-lg font-bold text-gray-900 line-clamp-1">
              {{ ticket.name }}
            </h3>
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
                  'text-yellow-700 bg-yellow-100': ticket.status === 'pending',
                  'text-green-700 bg-green-100': ticket.status === 'resolved',
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
          </div>

          <div class="flex flex-col text-wrap gap-3">
            <p class="text-sm text-gray-600 line-clamp-3">
              {{ ticket.description }}. Lorem ipsum, dolor sit amet consectetur
              adipisicing elit. Non ut fugit aspernatur nisi quod blanditiis
              illo fuga dolore.
            </p>

            <div
              class="flex justify-between items-center text-sm text-gray-500"
            >
              <p class="">
                {{
                  DateTime.fromISO(ticket.created_at).toFormat(
                    "dd MMMM yyyy, HH:mm"
                  )
                }}
              </p>
              <span class="flex flex-col text-right">
                <div class="flex justify-end items-center gap-1">
                  <i data-feather="clock" class="w-4 h-4"></i>
                  Diperbarui:
                </div>
                <span class="text-green-500">
                  {{
                    DateTime.fromISO(ticket.updated_at).toFormat(
                      "dd MMMM yyyy, HH:mm"
                    )
                  }}
                </span>
              </span>
            </div>
          </div>

          <RouterLink
            :to="{ name: 'app.ticket.detail', params: { code: ticket.id } }"
            class="flex justify-center w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded-lg transition-colors"
          >
            Selengkapnya
            <i data-feather="chevron-right" class="w-5 h-5 text-gray-400"></i>
          </RouterLink>
        </div>
      </div>
    </div>
  </div>
</template>
