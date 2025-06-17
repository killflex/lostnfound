import { defineStore } from "pinia";
import { axiosInstance } from "@/plugins/axios";
import { handleError } from "@/helpers/errorHelper";

export const useTicketStore = defineStore("ticket", {
  state: () => ({
    tickets: [],
    loading: false,
    error: null,
    success: null,
  }),

  actions: {
    async fetchTickets() {
      this.loading = true;

      try {
        const response = await axiosInstance.get(`item`);

        this.tickets = response.data.data;

        console.log(response);
      } catch (error) {
        this.error = handleError(error);
      } finally {
        this.loading = false;
      }
    },

    // async fetchTicket(code) {
    //   this.loading = true;

    //   try {
    //     const response = await axiosInstance.get(`item/${code}`);

    //     return response.data.data;
    //   } catch (error) {
    //     this.error = handleError(error);
    //   } finally {
    //     this.loading = false;
    //   }
    // },

    // async createTicket(payload) {
    //   this.loading = true;

    //   try {
    //     const response = await axiosInstance.post(`item`, payload);

    //     this.success = response.data.message;

    //     router.push({ name: "app.dashboard" });
    //   } catch (error) {
    //     this.error = handleError(error);
    //   } finally {
    //     this.loading = false;
    //   }
    // },

    // async createTicketReply(code, payload) {
    //   this.loading = true;

    //   try {
    //     const response = await axiosInstance.post(
    //       `/ticket-reply/${code}`,
    //       payload
    //     );

    //     this.success = response.data.message;

    //     return response.data.data;
    //   } catch (error) {
    //     this.error = handleError(error);
    //   } finally {
    //     this.loading = false;
    //   }
    // },
  },
});
