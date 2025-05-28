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
        const response = await axiosInstance.get(`ticket`, { params });

        this.tickets = response.data.data;
      } catch (error) {
        this.error = handleError(error);
      } finally {
        this.loading = false;
      }
    },

    async fetchTicket(code) {
      // TODO: Implement fetchTicket action
      // Steps:
      // 1. Set loading state
      // 2. Make API call to get ticket details
      // 3. Return ticket data
      // 4. Handle error
      // 5. Reset loading state
    },

    async createTicket(payload) {
      // TODO: Implement createTicket action
      // Steps:
      // 1. Set loading state
      // 2. Make API call to create ticket
      // 3. Set success message
      // 4. Redirect to dashboard
      // 5. Handle error
      // 6. Reset loading state
    },

    async createTicketReply(code, payload) {
      // TODO: Implement createTicketReply action
      // Steps:
      // 1. Set loading state
      // 2. Make API call to create reply
      // 3. Set success message
      // 4. Return reply data
      // 5. Handle error
      // 6. Reset loading state
    },
  },
});
