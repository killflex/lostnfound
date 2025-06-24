import { useAuthStore } from "@/stores/auth";
import { createRouter, createWebHistory } from "vue-router";

import Admin from "@/layouts/Admin.vue";
import Dashboard from "@/views/admin/Dashboard.vue";
import TicketList from "@/views/admin/ticket/TicketList.vue";
import TicketDetail from "@/views/admin/ticket/TicketDetail.vue";

import App from "@/layouts/App.vue";
import Auth from "@/layouts/Auth.vue";

import Login from "@/views/auth/Login.vue";
import Register from "@/views/auth/Register.vue";

import AppProfile from "@/views/app/Profile.vue";
import AppDashboard from "@/views/app/Dashboard.vue";
import AppTicketDetail from "@/views/app/TicketDetail.vue";
import AppTicketCreate from "@/views/app/TicketCreate.vue";
import AppTicketEdit from "@/views/app/TicketEdit.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      component: App,
      children: [
        {
          path: "",
          name: "app.dashboard",
          component: AppDashboard,
          meta: {
            requiresAuth: true,
            title: "Dashboard",
          },
        },
        {
          path: "ticket/:code",
          name: "app.ticket.detail",
          component: AppTicketDetail,
          meta: {
            requiresAuth: true,
            title: "Ticket Detail",
          },
        },
        {
          path: "ticket/:code/edit",
          name: "app.ticket.edit",
          component: AppTicketEdit,
          meta: {
            requiresAuth: true,
            title: "Ticket Edit",
          },
        },
        {
          path: "ticket/create",
          name: "app.ticket.create",
          component: AppTicketCreate,
        },
        {
          path: "profile",
          name: "app.profile",
          component: AppProfile,
          meta: {
            requiresAuth: true,
            title: "Profile",
          },
        },
      ],
    },
    {
      path: "/admin",
      component: Admin,
      children: [
        {
          path: "dashboard",
          name: "admin.dashboard",
          component: Dashboard,
          meta: {
            requiresAuth: true,
            title: "Dashboard",
          },
        },
        {
          path: "ticket",
          name: "admin.ticket",
          component: TicketList,
          meta: {
            requiresAuth: true,
            title: "Ticket",
          },
        },
        {
          path: "ticket/:code",
          name: "admin.ticket.detail",
          component: TicketDetail,
          meta: {
            requiresAuth: true,
            title: "Ticket Detail",
          },
        },
      ],
    },
    {
      path: "/auth",
      component: Auth,
      children: [
        {
          path: "login",
          name: "login",
          component: Login,
        },
        {
          path: "register",
          name: "register",
          component: Register,
        },
      ],
    },
  ],
});

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();

  if (to.meta.requiresAuth) {
    if (authStore.token) {
      try {
        if (!authStore.user) {
          await authStore.checkAuth();
        }

        next();
      } catch (error) {
        next({ name: "login" });
      }
    } else {
      next({ name: "login" });
    }
  } else if (to.meta.requiresUnauth && authStore.token) {
    next({ name: "dashboard" });
  } else {
    next();
  }
});

export default router;
