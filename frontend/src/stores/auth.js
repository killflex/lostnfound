import { defineStore } from "pinia";
import { axiosInstance } from "@/plugins/axios";
import { handleError } from "@/helpers/errorHelper";
import router from "@/router";
import Cookies from "js-cookie";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: null,
    loading: false,
    error: null,
    success: null,
  }),

  getters: {
    token: () => Cookies.get("token"),
    isAuthenticated: (state) => !!state.user,
  },
  actions: {
    async login(credentials) {
      this.loading = true;
      this.error = null;

      try {
        const response = await axiosInstance.post("/login", credentials);
        const token = response.data.token;
        const role = response.data.role;

        Cookies.set("token", token);

        this.success = response.data.message;

        if (role === "admin") {
          router.push({ name: "admin.dashboard" });
        } else {
          router.push({ name: "app.dashboard" });
        }
      } catch (error) {
        this.error = handleError(error);
      } finally {
        this.loading = false;
      }
    },

    async register(credentials) {
      this.loading = true;

      try {
        const response = await axiosInstance.post(`/register`, credentials);

        this.success = response.data.message;

        router.push({ name: "login" });
        alert("Register Success");
      } catch (error) {
        this.error = handleError(error);
      } finally {
        this.loading = false;
      }
    },

    async logout() {
      this.loading = true;

      try {
        const response = await axiosInstance.post(`/logout`);

        Cookies.remove("token");

        this.user = null;
        this.error = null;

        this.success = response.data.message;

        router.push({ name: "login" });
      } catch (error) {
        this.error = handleError(error);
      } finally {
        this.loading = false;
      }
    },

    async fetchCurrentUser() {
      this.loading = true;
      this.error = null;
      this.success = null;

      try {
        const response = await axiosInstance.get("/user");

        return response.data.data;
      } catch (error) {
        this.error = handleError(error);
        this.user = null;
      } finally {
        this.loading = false;
      }
    },

    async updateCurrentUser(payload) {
      this.loading = true;
      this.error = null;

      try {
        if (!payload || Object.keys(payload).length === 0) {
          this.error = {
            general: ["No data provided to update."],
          };
          return;
        }

        // Create FormData if payload contains password
        const data = payload.password ? new FormData() : payload;

        if (payload.password) {
          Object.keys(payload).forEach((key) => {
            data.append(key, payload[key]);
          });
        }

        const response = await axiosInstance.put("/user", data, {
          headers: {
            "Content-Type": payload.password
              ? "multipart/form-data"
              : "application/json",
          },
        });

        if (response.data.success) {
          // Update user state
          this.user = response.data.data;
          this.success = response.data.message;

          // Refresh token if email was changed
          if (payload.email && payload.email !== this.user.email) {
            await this.checkAuth();
          }
        }

        return response.data;
      } catch (error) {
        this.error = handleError(error);
        return null;
      } finally {
        this.loading = false;
      }
    },

    async checkAuth() {
      if (!this.token) {
        this.user = null;
        return false;
      }

      this.loading = true;
      this.error = null;

      try {
        const response = await axiosInstance.get("/user");
        this.user = response.data;
        return true;
      } catch (error) {
        this.error = handleError(error);
        this.user = null;
        Cookies.remove("token");

        if (error.response?.status === 401) {
          router.push({
            name: "login",
            query: {
              redirect: router.currentRoute.value.fullPath,
            },
          });
        }
        return false;
      } finally {
        this.loading = false;
      }
    },
  },
});
