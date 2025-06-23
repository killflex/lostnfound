import { defineStore } from "pinia";
import { axiosInstance } from "@/plugins/axios";
import { handleError } from "@/helpers/errorHelper";
import router from "@/router";

export const useTicketStore = defineStore("ticket", {
  state: () => ({
    tickets: [],
    loading: false,
    error: null,
    success: null,
  }),

  actions: {
    async fetchTickets(params) {
      this.loading = true;

      try {
        const response = await axiosInstance.get(`item`, { params });

        this.tickets = response.data.data;
      } catch (error) {
        this.error = handleError(error);
      } finally {
        this.loading = false;
      }
    },

    async fetchTicket(code) {
      this.loading = true;

      try {
        const response = await axiosInstance.get(`item/${code}`);

        return response.data.data;
      } catch (error) {
        this.error = handleError(error);
      } finally {
        this.loading = false;
      }
    },

    async createTicket(payload) {
      try {
        this.loading = true;
        this.error = null;

        const response = await axiosInstance.post(`item`, payload, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });

        if (response.data.success) {
          this.success = response.data.message;
          router.push({ name: "app.dashboard" });
        }
      } catch (err) {
        this.error = err.response?.data?.errors || {
          general: ["Something went wrong. Please try again later."],
        };
        console.error("Create ticket error:", err);
      } finally {
        this.loading = false;
      }
    },

    async editTicket(code, payload) {
      try {
        this.loading = true;
        this.error = null;

        // Create FormData object for multipart/form-data
        const formData = new FormData();

        // Only append fields that have values (partial update)
        if (payload.name) formData.append("name", payload.name);
        if (payload.description)
          formData.append("description", payload.description);
        if (payload.location) formData.append("location", payload.location);
        if (payload.type) formData.append("type", payload.type);
        if (payload.status) formData.append("status", payload.status);

        // Add new image if provided
        if (payload.image instanceof File) {
          formData.append("image", payload.image);
        }

        // Add _method field for Laravel to handle PUT request
        formData.append("_method", "PUT");

        const response = await axiosInstance.post(`item/${code}`, payload, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });

        if (response.data.success) {
          this.success = response.data.message;
          router.push({
            name: "app.ticket.detail",
            params: { code: code }, // Perbaikan: gunakan code dari parameter
          });
        }
      } catch (err) {
        this.error = err.response?.data?.errors || {
          general: ["Something went wrong. Please try again later."],
        };
        console.error("Edit ticket error:", err);
      } finally {
        this.loading = false;
      }
    },

    async createTicketReply(code, payload) {
      this.loading = true;

      try {
        const response = await axiosInstance.post(`/chat/${code}`, payload);

        this.success = response.data.message;

        return response.data.data;
      } catch (error) {
        this.error = handleError(error);
      } finally {
        this.loading = false;
      }
    },
  },
});
